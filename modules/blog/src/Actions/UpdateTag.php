<?php

namespace Modules\Blog\Actions;

use Illuminate\Support\Arr;
use Modules\Blog\Models\Tag;

final class UpdateTag
{
    public function handle(Tag $tag, array $data): Tag
    {
        $data = Arr::only($data, ['name', 'slug']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['slug'] ?? ($data['name'] ?? ''));

        $tag->update($data);

        return $tag->fresh();
    }
}
