<?php

namespace Modules\Blog\Actions;

use Modules\Blog\Models\Tag;

final class DeleteTag
{
    public function handle(Tag $tag): void
    {
        $tag->delete();
    }
}
