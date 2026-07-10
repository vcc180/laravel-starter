<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstalledPackage extends Model
{
    protected $fillable = ['type', 'slug', 'version', 'path', 'active'];
}
