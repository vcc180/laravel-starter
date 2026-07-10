<?php

namespace Modules\Blog\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class CategoryRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
