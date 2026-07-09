<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public static $wrap = 'data';

    public static function collection($resource)
    {
        return parent::collection($resource);
    }
}
