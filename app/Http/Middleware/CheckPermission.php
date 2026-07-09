<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (empty($permissions)) {
            return $next($request);
        }

        if (Auth::user()->hasPermission($permissions)) {
            return $next($request);
        }

        abort(403, 'Sem permissão para esta ação.');
    }
}
