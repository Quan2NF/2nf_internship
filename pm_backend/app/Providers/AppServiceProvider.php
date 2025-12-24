<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implements\AuthService;
use App\Services\Interfaces\IBaseService;
use App\Services\Implements\BaseService;
use App\Services\Interfaces\IUserService;
use App\Services\Implements\UserService;
use App\Repositories\Implements\UserRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    
    
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IBaseRepository::class, BaseRepository::class);

    // Service
         $this->app->bind(IAuthService::class, AuthService::class);
         $this->app->bind(IUserService::class, UserService::class);
         $this->app->bind(IBaseService::class, BaseService::class);

         $this->autoBind(app_path('Repositories/Interfaces'), 'App\\Repositories\\Interfaces', 'App\\Repositories\\Implements');

        // Tự động bind Services
        $this->autoBind(app_path('Services/Interfaces'), 'App\\Services\\Interfaces', 'App\\Services\\Implements');
    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
    }
    protected function autoBind(string $interfacePath, string $interfaceNamespace, string $implementationNamespace)
    {
        $files = File::allFiles($interfacePath);

        foreach ($files as $file) {
            $interfaceClass = $interfaceNamespace . '\\' . $file->getBasename('.php');
            // Lấy tên implementation dựa trên convention: IXXX -> XXX
            $implementationClass = $implementationNamespace . '\\' . Str::replaceFirst('I', '', $file->getBasename('.php'));

            if (class_exists($implementationClass)) {
                $this->app->bind($interfaceClass, $implementationClass);
            }
        }
    }
}
