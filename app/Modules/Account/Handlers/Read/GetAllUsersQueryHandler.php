<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Read;

use App\Shared\Handler;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Modules\Account\Queries\GetAllUsersQuery;

final class GetAllUsersQueryHandler extends Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(GetAllUsersQuery $query): array
    {
        return $this->userRepository->all();
    }
}
