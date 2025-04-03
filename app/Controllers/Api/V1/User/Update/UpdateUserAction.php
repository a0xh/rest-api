<?php declare(strict_types=1);

namespace App\Controllers\Api\V1\User\Update;

use App\Shared\Controller as Action;
use App\Modules\Account\Requests\UpdateUserRequest;
use App\Modules\Account\Commands\UpdateUserCommand;
use App\Contracts\Interface\Buses\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Interaction\Responses\MessageResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class UpdateUserAction extends Action
{
	private readonly CommandBusInterface $commandBus;

	public function __construct(CommandBusInterface $commandBus)
	{
		$this->commandBus = $commandBus;
	}

	#[Route(methods: ['PUT'], uri: '/users/{id}/update')]
	public function __invoke(
		string $id, UpdateUserRequest $request): MessageResponse
	{
		return new UpdateUserResponder()->respond(
			result: $this->commandBus->send(
				command: UpdateUserCommand::fromRequest(
					request: $request
				)
			)
		);
	}
}
