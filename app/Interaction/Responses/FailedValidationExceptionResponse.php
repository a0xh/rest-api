<?php declare(strict_types=1);

namespace App\Interaction\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\MessageBag;

final class FailedValidationExceptionResponse implements Responsable
{
    /**
     * Constructs a new FailedValidationExceptionResponse instance.
     *
     * @param MessageBag $errors
     */
    public function __construct(private MessageBag $errors) {}

    /**
     * Converts the response to a JSON response.
     *
     * @param mixed $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'result' => [
                    'message' => __('Validation Error.'),
                    'errors' => $this->errors
                ],
                'metadata' => [
                    'request_id' => Context::get(key: 'request_id'),
                    'timestamp' => Context::get(key: 'timestamp')
                ],
            ],
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
