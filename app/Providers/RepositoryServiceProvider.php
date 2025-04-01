<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Contracts\Interface\Repositories\PermissionRepositoryInterface;
use App\Repositories\PermissionRepository;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Repositories\MediaRepository;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: RoleRepositoryInterface::class,
            concrete: RoleRepository::class
        );
        
        $this->app->bind(
            abstract: PermissionRepositoryInterface::class,
            concrete: PermissionRepository::class
        );
        
        $this->app->bind(
            abstract: MediaRepositoryInterface::class,
            concrete: MediaRepository::class
        );
        
        $this->app->bind(
            abstract: UserRepositoryInterface::class,
            concrete: UserRepository::class
        );
    }
}
