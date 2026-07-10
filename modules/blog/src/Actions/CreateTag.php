<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Tag;

final class CreateTag
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(array $data): Tag
    {
        $data = Arr::only($data, ['name', 'slug']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['slug'] ?? ($data['name'] ?? ''));

        $tag = Tag::create($data);

        $this->hooks->doAction('blog.tag.created', $tag);

        return $tag;
    }
}
