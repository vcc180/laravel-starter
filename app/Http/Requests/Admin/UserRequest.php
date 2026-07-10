<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class UserRequest extends AdminRequest
{
    public function rules(): array
    {
        $userId = $this->route('user');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        } elseif ($this->filled('password')) {
            $rules['password'] = ['nullable', 'string', 'min:6', 'confirmed'];
        }

        return $rules;
    }
}
