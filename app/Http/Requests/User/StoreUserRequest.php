<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'department_id' => ['sometimes', 'nullable', 'exists:departments,id'],
            'role_id' => ['required', 'numeric'],
        ];
    }

    public function userData()
    {
        return $this->only(array_keys($this->rules()));
    }

    public function messages()
    {
        return [
            'role_id.required' => 'The role field is required.'
        ];
    }
}
