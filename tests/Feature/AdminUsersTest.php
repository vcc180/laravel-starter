<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function seeder()
    {
        $this->artisan('db:seed', ['--force' => true]);
    }

    public function test_admin_users_page_loads()
    {
        $this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->actingAs($user, 'web');

        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Usuários');
    }
}
