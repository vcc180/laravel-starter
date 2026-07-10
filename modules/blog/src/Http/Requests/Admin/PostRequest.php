<?php

namespace Modules\Blog\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class PostRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
            'body' => ['nullable', 'string'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:blog_tags,id'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}
