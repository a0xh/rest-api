<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Read;

use App\Shared\Handler;
use App\Modules\Account\Queries\GetUserByIdQuery;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Entities\User;

final class GetUserByIdQueryHandler extends Handler
{
    private readonly UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(GetUserByIdQuery $query): ?User
    {
        return $this->userRepository->findById(id: $query->getId());
    }
}
