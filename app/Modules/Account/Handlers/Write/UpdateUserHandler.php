<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Modules\Account\Commands\UpdateUserCommand;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Modules\Account\Events\MediaWasUpdated;
use Illuminate\Support\Facades\Log;
use App\Entities\User;

final class UpdateUserHandler extends Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function handle(UpdateUserCommand $command): bool
    {
        $user = $this->userRepository->findById(id: $command->id);

        $user->setFirstName(firstName: $command->firstName);
        $user->setLastName(lastName: $command->lastName);

        if (!empty($command->patronymic)) {
            $user->setPatronymic(patronymic: $command->patronymic);
        }

        $user->setEmail(email: $command->email);

        if (!empty($command->phone)) {
            $user->setPhone(phone: $command->phone);
        }

        $user->setPassword(password: $command->password);

        if (!empty($command->status)) {
            $user->setStatus(status: $command->status);
        }

        if (!empty($command->roleId)) {
            $role = $this->roleRepository->findById(
                id: $command->roleId
            );

            $user->setRole(role: $role);
        }

        $this->userRepository->save(user: $user);

        if (!empty($command->avatar)) {
            event(new MediaWasUpdated(
                avatar: $command->avatar,
                user: $user
            ));
        }

        return $user ? true : false;
    }
}
