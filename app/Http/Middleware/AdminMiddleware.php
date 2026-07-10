<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        try {
            $hasAdmin = Auth::user()->hasPermission('admin.access');
        } catch (\Throwable) {
            $hasAdmin = true;
        }

        if (!$hasAdmin) {
            abort(403, 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}
