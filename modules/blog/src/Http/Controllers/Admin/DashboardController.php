<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Modules\Blog\Actions\GetBlogStats;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke(GetBlogStats $stats)
    {
        return view('blog::admin.dashboard', [
            'title' => 'Dashboard do Blog',
            'stats' => $stats->handle(),
        ]);
    }
}
