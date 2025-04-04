<?php declare(strict_types=1);

namespace App\Modules\Auth\Handlers\Read;

use App\Shared\Handler;
use App\Modules\Auth\Queries\SignOutUserQuery;
use App\Services\Authenticate;

final class SignOutUserQueryHandler extends Handler
{
    public function __construct(
        private Authenticate $authenticate
    ) {}

    public function handle(SignOutUserQuery $query): bool
    {
        try {
            $this->authenticate->logout();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
