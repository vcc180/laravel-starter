<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminLoginTest extends TestCase
{
    /** @test */
    public function unauthenticated_user_is_redirected_to_admin_login()
    {
        Auth::logout();

        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }
}
