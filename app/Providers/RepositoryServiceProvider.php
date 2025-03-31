<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Interface\Repository\Storage\RoleStorageRepositoryInterface;
use App\Repositories\Query\RoleQueryRepository;
use App\Contracts\Interface\Repository\Storage\PermissionStorageRepositoryInterface;
use App\Repositories\Query\PermissionQueryRepository;
use App\Contracts\Interface\Repository\Storage\MediaStorageRepositoryInterface;
use App\Repositories\Query\MediaQueryRepository;
use App\Contracts\Interface\Repository\Storage\UserStorageRepositoryInterface;
use App\Repositories\Query\UserQueryRepository;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: RoleStorageRepositoryInterface::class,
            concrete: RoleQueryRepository::class
        );
        
        $this->app->bind(
            abstract: PermissionStorageRepositoryInterface::class,
            concrete: PermissionQueryRepository::class
        );
        
        $this->app->bind(
            abstract: MediaStorageRepositoryInterface::class,
            concrete: MediaQueryRepository::class
        );
        
        $this->app->bind(
            abstract: UserStorageRepositoryInterface::class,
            concrete: UserQueryRepository::class
        );
    }
}
