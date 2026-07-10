<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class LocaleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', 'max:8'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
