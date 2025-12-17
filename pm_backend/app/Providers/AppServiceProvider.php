<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implements\AuthService;
use App\Services\Interfaces\IUserService;
use App\Services\Implements\UserService;
use App\Repositories\Implements\UserRepository;
use App\Repositories\Interfaces\IUserRepository;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    
    
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);

    // Service
         $this->app->bind(IAuthService::class, AuthService::class);
         $this->app->bind(IUserService::class, UserService::class);

    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
    }
}
