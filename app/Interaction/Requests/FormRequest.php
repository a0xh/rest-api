<?php declare(strict_types=1);

namespace App\Interaction\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Interaction\Responses\FailedValidationExceptionResponse;
use Illuminate\Contracts\Validation\Validator;

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
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = new FailedValidationExceptionResponse(
            errors: $validator->errors()
        );

        throw new HttpResponseException(
            response: $response->toResponse(request: $this->request)
        );
    }
}
