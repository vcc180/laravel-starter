<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'event',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(?int $userId, string $event, string $description = ''): void
    {
        static::create([
            'user_id' => $userId,
            'name' => (string) $userId,
            'event' => $event,
            'description' => $description,
        ]);
    }
}
