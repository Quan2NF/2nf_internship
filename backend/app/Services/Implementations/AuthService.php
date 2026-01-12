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
use App\Data\Auth\ResetPasswordData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function login(LoginData $data): UserData
    {
        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
        ];

        if (!Auth::attempt($credentials)) {
            throw new BusinessException('PASSWORD_FAIL', 400);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            throw new BusinessException('DATA_NOT_FOUND', 400);
        }
        Auth::login($user);

        return UserData::fromModel($user);
    }

    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
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

    public function resetPassword(ResetPasswordData $data): void
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $data->email)
            ->first();

        if (!$record) {
            throw new BusinessException('TOKEN_INVALID', 400);
        }

        // ✅ SO SÁNH TOKEN ĐÚNG
        if (!Hash::check($data->token, $record->token)) {
            throw new BusinessException('TOKEN_INVALID', 400);
        }

        // ⏱ Check expire
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            throw new BusinessException('TOKEN_EXPIRED', 400);
        }

        $user = User::where('email', $record->email)->first();

        if (!$user) {
            throw new BusinessException('DATA_NOT_FOUND', 400);
        }

        if ($user->trashed()) {
            throw new BusinessException('USER_INACTIVE', 403);
        }

        // 🔐 Update password
        $user->update([
            'password' => Hash::make($data->password),
        ]);

        // 🧹 Xoá token sau khi dùng
        DB::table('password_reset_tokens')
            ->where('email', $record->email)
            ->delete();
    }

}
