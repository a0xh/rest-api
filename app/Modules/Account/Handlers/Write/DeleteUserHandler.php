<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Modules\Account\Commands\DeleteUserCommand;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Modules\Account\Events\MediaWasDeleted;

final class DeleteUserHandler extends Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(DeleteUserCommand $command): bool
    {
        $user = $this->userRepository->findById(id: $command->id);

        event(new MediaWasDeleted(userId: $command->id));

        $this->userRepository->remove(user: $user);

        return $user ? true : false;
    }
}
