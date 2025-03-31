<?php declare(strict_types=1);

namespace App\Providers;

use App\Shared\Application;
use Illuminate\Support\ServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider(name: 'doctrine',
            callback: function (Application $app, array $config): AuthService {
                return new AuthService(em: $app->make(
                    abstract: EntityManagerInterface::class
                ));
            }
        );
    }
}
