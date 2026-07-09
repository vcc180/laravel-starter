<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminLoginTest extends TestCase
{
    /** @test */
    public function admin_can_login_with_correct_credentials()
    {
        $email = 'admin-test-'.uniqid().'@example.com';
        $password = 'test123';
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->from('/admin/login')
            ->post('/admin/login', [
                'email' => $email,
                'password' => $password,
            ]);

        $response->assertRedirect('/admin');
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    /** @test */
    public function admin_cannot_login_with_invalid_credentials()
    {
        $response = $this->from('/admin/login')
            ->post('/admin/login', [
                'email' => 'admin-test-'.uniqid().'@example.com',
                'password' => 'wrong',
            ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
    }
}
