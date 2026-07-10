<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class ExampleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
