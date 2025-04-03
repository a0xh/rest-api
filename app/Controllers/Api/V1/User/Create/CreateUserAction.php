<?php declare(strict_types=1);

namespace App\Controllers\Api\V1\User\Create;

use App\Shared\Controller as Action;
use App\Modules\Account\Requests\CreateUserRequest;
use App\Modules\Account\Commands\CreateUserCommand;
use App\Contracts\Interface\Buses\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Interaction\Responses\MessageResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class CreateUserAction extends Action
{
	private readonly CommandBusInterface $commandBus;

	public function __construct(CommandBusInterface $commandBus)
	{
		$this->commandBus = $commandBus;
	}

	#[Route(methods: ['POST'], uri: '/users/create')]
	public function __invoke(
		CreateUserRequest $request): MessageResponse
	{
		$command = CreateUserCommand::fromRequest(request: $request);

		return new CreateUserResponder()->respond(
			result: $this->commandBus->send(command: $command)
		);
	}
}
