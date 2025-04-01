<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Facades\Context;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;

    /**
     * Handle failed validation.
     *
     * @param Validator $validator
     * 
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response: new JsonResponse(
                data: [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'result' => [
                        'message' => __('Validation Error.'),
                        'errors' => $validator->errors()
                    ],
                    'metadata' => [
                        'request_id' => Context::get(key: 'request_id'),
                        'timestamp' => Context::get(key: 'timestamp')
                    ],
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
