<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['inactive', 'deleted', '0']);
    }

    public function scopeFilter($query, $columns = [])
    {
        foreach ($columns as $column => $type) {
            $value = request("filter.$column", null);
            if ($value === null) {
                continue;
            }

            if (is_int($column)) {
                $column = $type;
                $type = 'eq';
            }

            $type = $type ?: 'like';

            if ($type === 'like') {
                $query->where($column, 'like', "%{$value}%");
            } elseif ($type === 'eq') {
                $query->where($column, $value);
            }
        }
    }
}
