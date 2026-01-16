<?php

namespace App\Services;

use App\Data\LoginData;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Data\ForgotPasswordData;
use App\Data\ResetPasswordData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function login(LoginData $data): array
    {
        try {
            // Step 1.2.1: fetch active user by email
            $user = $this->users->findActiveByEmail($data->email);
            if (!$user) {
                throw new BusinessException('ERR_LOGIN_FAILED', 401);
            }

            // Step 1.2.2: check password
            if (!Hash::check($data->password, $user->password)) {
                throw new BusinessException('ERR_LOGIN_FAILED', 401);
            }

            // Step 1.3: create session (Sanctum cookie-based auth)
            try {
                Auth::login($user);
                session()->regenerate();
            } catch (\Throwable $e) {
                throw new BusinessException('ERR_UNKNOWN', 500);
            }

            // Step 1.4: check isAdmin
            $isAdmin = $this->users->userIsAdmin($user);

            // Step 1.5: build response data
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? url($user->avatar) : null,
                'isAdmin' => $isAdmin,
            ];
        } catch (BusinessException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_UNKNOWN', 500);
        }
    }

    public function profile(User $user): array
    {
        try {
            $isAdmin = $this->users->userIsAdmin($user);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? url($user->avatar) : null,
                'isAdmin' => $isAdmin,
            ];
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_UNKNOWN', 500);
        }
    }

    public function forgotPassword(ForgotPasswordData $data): bool
    {
        try {
            $user = $this->users->findByEmail($data->email);
            if (!$user) {
                throw new BusinessException('ERR_USER_NOT_FOUND', 404);
            }

            $status = Password::sendResetLink(['email' => $data->email]);

            if ($status !== Password::RESET_LINK_SENT) {
                throw new BusinessException('ERR_RESET_LINK_FAILED', 500);
            }

            return true;
        } catch (BusinessException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_UNKNOWN', 500);
        }
    }

    public function resetPassword(ResetPasswordData $data): bool
    {
        try {
            $status = Password::reset(
                [
                    'email' => $data->email,
                    'password' => $data->password,
                    'password_confirmation' => $data->password,
                    'token' => $data->token,
                ],
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            if ($status !== Password::PASSWORD_RESET) {
                throw new BusinessException('ERR_RESET_PASSWORD_FAILED', 400);
            }

            return true;
        } catch (BusinessException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_UNKNOWN', 500);
        }
    }

    public function logout(Request $request): bool
    {
        try {
            $user = $request->user();
            if (!$user) {
                throw new BusinessException('ERR_UNAUTHENTICATED', 401);
            }

            // Token-based (Sanctum personal access token): revoke only current token
            $currentToken = $user->currentAccessToken();
            if ($currentToken) {
                $currentToken->delete();
            }

            // Session/cookie-based: logout and invalidate the session
            if ($request->hasSession()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return true;
        } catch (BusinessException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_LOGOUT_FAILED', 500);
        }
    }
}


