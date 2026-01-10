<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\IAuthService;
use App\Repositories\Interfaces\IUserRepository;
use App\Data\Auth\LoginData;
use App\Data\Auth\AuthResponseData;
use App\Data\User\UserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;   
use App\Data\Auth\ForgotPasswordData; 
use App\Exceptions\Domain\BusinessException;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function login(LoginData $data): AuthResponseData
    {
        $user = $this->userRepository->findByEmail($data->email);

        if (!$user) {
            throw new BusinessException('DATA_NOT_FOUND', 400);
        }

        if (!Hash::check($data->password, $user->password)) {
            throw new BusinessException('PASSWORD_FAIL', 400);
        }

        $token = $user->createToken('api')->plainTextToken;

        return new AuthResponseData(
            token: $token,
            token_type: 'Bearer',
            user: UserData::fromModel($user)
        );
    }

    public function logout(User $user): void
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        $token?->delete();
    }

    public function forgotPassword(ForgotPasswordData $data): void
    {
        $user = User::where('email', $data->email)->first();

        if (!$user) {
            throw new BusinessException('DATA_NOT_FOUND', 400);
        }

        if ($user->trashed()) {
            throw new BusinessException('USER_INACTIVE', 403);
        }

        $status = Password::sendResetLink([
            'email' => $data->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new BusinessException('MAIL_SEND_FAILED', 500);
        }
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();

                $user->tokens()->delete(); // logout all devices
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new BusinessException(__($status), 400);
        }
    }
}
