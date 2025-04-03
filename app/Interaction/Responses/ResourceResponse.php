<?php declare(strict_types=1);

namespace App\Interaction\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

final class ResourceResponse implements Responsable
{
    public function __construct(
        private mixed $data,
        private int $status
    ) {}
    
    public function toResponse($request): JsonResponse
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        return new JsonResponse(
            data: [
                'status' => $this->status,
                'data' => $this->data,
                'metadata' => [
                    'request_id' => $requestId,
                    'timestamp' => $timestamp
                ],
            ],
            status: $this->status
        );
    }
}
