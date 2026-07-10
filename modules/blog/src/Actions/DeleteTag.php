<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Modules\Blog\Models\Tag;

final class DeleteTag
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Tag $tag): void
    {
        $this->hooks->doAction('blog.tag.deleting', $tag);

        $tag->delete();

        $this->hooks->doAction('blog.tag.deleted', $tag);
    }
}
