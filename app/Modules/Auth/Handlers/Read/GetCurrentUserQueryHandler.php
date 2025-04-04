<?php declare(strict_types=1);

namespace App\Modules\Auth\Handlers\Read;

use App\Shared\Handler;
use App\Modules\Auth\Queries\GetCurrentUserQuery;
use App\Services\Authenticate;
use App\Entities\User;

final class GetCurrentUserQueryHandler extends Handler
{
    public function __construct(
        private Authenticate $authenticate
    ) {}

    public function handle(GetCurrentUserQuery $query): ?User
    {
        return $this->authenticate->getMe();
    }
}
