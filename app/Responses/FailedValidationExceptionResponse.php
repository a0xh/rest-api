<?php declare(strict_types=1);

namespace App\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\MessageBag;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Facades\Context;

final class FailedValidationExceptionResponse implements Responsable
{
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
