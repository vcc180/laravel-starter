<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Tag;

final class UpdateTag
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Tag $tag, array $data): Tag
    {
        $data = Arr::only($data, ['name', 'slug']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['slug'] ?? ($data['name'] ?? ''));

        $tag->update($data);

        $this->hooks->doAction('blog.tag.updated', $tag->fresh());

        return $tag->fresh();
    }
}
