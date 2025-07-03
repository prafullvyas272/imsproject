<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email,' . $this->user?->id],
            'department_id' => ['sometimes', 'nullable', 'exists:departments,id'],
            'role_id' => ['required', 'numeric'],
        ];
    }

    public function userData()
    {
        return $this->only(array_keys($this->rules()));
    }
}
