<?php declare(strict_types=1);

namespace App\Controllers\Api\V1\User\Delete;

use App\Shared\Controller as Action;
use App\Modules\Account\Requests\DeleteUserRequest;
use App\Modules\Account\Commands\DeleteUserCommand;
use App\Contracts\Interface\Buses\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Interaction\Responses\MessageResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class DeleteUserAction extends Action
{
	private readonly CommandBusInterface $commandBus;

	public function __construct(CommandBusInterface $commandBus)
	{
		$this->commandBus = $commandBus;
	}

	#[Route(methods: ['DELETE'], uri: '/users/{id}/delete')]
	public function __invoke(
		string $id, DeleteUserRequest $request): MessageResponse
	{
		$command = DeleteUserCommand::fromRequest(request: $request);
		
		return new DeleteUserResponder()->respond(
			result: $this->commandBus->send(command: $command)
		);
	}
}
