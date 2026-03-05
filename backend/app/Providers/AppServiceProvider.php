<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // global bindings only
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = $this->app;

        Model::preventLazyLoading(!$app->isProduction());
        Model::preventSilentlyDiscardingAttributes(!$app->isProduction());

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return rtrim(config('app.frontend_url'), '/')
                . "/reset-password?token=$token&email=" . urlencode($user->email);
        });
    }
}
