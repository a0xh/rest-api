<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Interface\RoleRepositoryInterface;
use App\Repositories\Query\RoleQueryRepository;
use App\Contracts\Interface\PermissionRepositoryInterface;
use App\Repositories\Query\PermissionQueryRepository;
use App\Contracts\Interface\MediaRepositoryInterface;
use App\Repositories\Query\MediaQueryRepository;
use App\Contracts\Interface\UserRepositoryInterface;
use App\Repositories\Query\UserQueryRepository;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: RoleRepositoryInterface::class,
            concrete: RoleQueryRepository::class
        );
        
        $this->app->bind(
            abstract: PermissionRepositoryInterface::class,
            concrete: PermissionQueryRepository::class
        );
        
        $this->app->bind(
            abstract: MediaRepositoryInterface::class,
            concrete: MediaQueryRepository::class
        );
        
        $this->app->bind(
            abstract: UserRepositoryInterface::class,
            concrete: UserQueryRepository::class
        );
    }
}
