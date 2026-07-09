<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->is('admin*')) {
            return '/admin/login';
        }

        return route('login');
    }
}
