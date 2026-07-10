<?php

namespace Modules\Blog\Actions;

use Illuminate\Support\Arr;
use Modules\Blog\Models\Tag;

final class CreateTag
{
    public function handle(array $data): Tag
    {
        $data = Arr::only($data, ['name', 'slug']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['slug'] ?? ($data['name'] ?? ''));

        return Tag::create($data);
    }
}
