<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Tag;
use App\Models\Role;
use App\Models\Permission;

class AdminTagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->ensureBlogTagPermission();
    }

    private function ensureBlogTagPermission(): void
    {
        $permission = Permission::updateOrCreate(
            ['slug' => 'blog.tags'],
            ['name' => 'Gerenciar tags do blog', 'module' => 'blog', 'description' => 'Acesso a tags do blog']
        );

        $role = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Acesso total']
        );

        $role->permissions()->syncWithoutDetaching([$permission->id]);
    }

    private function actingAsAdmin(): ?User
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->actingAs($user, 'web');

        return $user;
    }

    /** @test */
    public function admin_tags_index_loads()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/tags');

        $response->assertStatus(200);
        $response->assertSee('Nova');
    }

    /** @test */
    public function admin_tags_can_create_tag()
    {
        $this->actingAsAdmin();

        $response = $this->post('/admin/tags', [
            'name' => 'Nova tag',
            'slug' => 'nova-tag',
        ]);

        $response->assertRedirect('/admin/tags');
        $this->assertDatabaseHas('blog_tags', ['name' => 'Nova tag']);
    }

    /** @test */
    public function admin_tags_can_update_tag()
    {
        $this->actingAsAdmin();

        $tag = Tag::create(['name' => 'Antiga', 'slug' => 'antiga']);

        $response = $this->put('/admin/tags/'.$tag->id, [
            'name' => 'Atualizada',
            'slug' => 'atualizada',
        ]);

        $response->assertRedirect('/admin/tags');
        $this->assertDatabaseHas('blog_tags', ['name' => 'Atualizada']);
    }

    /** @test */
    public function admin_tags_can_delete_tag()
    {
        $this->actingAsAdmin();

        $tag = Tag::create(['name' => 'Remover', 'slug' => 'remover']);

        $response = $this->delete('/admin/tags/'.$tag->id);

        $response->assertRedirect('/admin/tags');
        $this->assertDatabaseMissing('blog_tags', ['id' => $tag->id]);
    }
}
