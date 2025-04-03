<?php declare(strict_types=1);

namespace App\Modules\Account\Requests;

use App\Interaction\Requests\FormRequest;

final class DeleteUserRequest extends FormRequest
{
	/**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'uuid', 'exists:users,id']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(input: ['id' => $this->route('id')]);
    }
}
