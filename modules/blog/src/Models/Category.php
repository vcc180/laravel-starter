<?php

namespace Modules\Blog\Models;

use App\Models\BaseModel;

class Category extends BaseModel
{
    protected $table = 'blog_categories';

    protected $fillable = ['name', 'slug'];
}
