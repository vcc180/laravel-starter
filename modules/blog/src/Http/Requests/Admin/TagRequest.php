<?php

namespace Modules\Blog\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class TagRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_tags,slug'],
        ];
    }
}
