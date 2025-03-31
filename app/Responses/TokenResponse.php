<?php declare(strict_types=1);

namespace App\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

final class TokenResponse implements Responsable
{
    public function __construct(
        private string $message,
        private ?string $token = null,
        private int $status,
    ) {}

    public function toResponse($request): JsonResponse
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        return new JsonResponse(
            data: [
                'status' => $this->status,
                'data' => [
                    'message' => __(key: $this->message),
                    'access_token' => __(key: $this->token),
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60
                ],
                'metadata' => [
                    'request_id' => $requestId,
                    'timestamp' => $timestamp
                ],
            ],
            status: $this->status
        );
    }
}
