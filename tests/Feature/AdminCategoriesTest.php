<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Category;

class AdminCategoriesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function actingAsAdmin(): ?User
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->actingAs($user, 'web');

        return $user;
    }

    /** @test */
    public function admin_categories_index_loads()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/categories');

        $response->assertStatus(200);
        $response->assertSee('Nova');
    }

    /** @test */
    public function admin_categories_can_create_category()
    {
        $this->actingAsAdmin();

        $response = $this->post('/admin/categories', [
            'name' => 'Nova categoria',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('blog_categories', ['name' => 'Nova categoria']);
    }

    /** @test */
    public function admin_categories_can_update_category()
    {
        $this->actingAsAdmin();

        $category = Category::create(['name' => 'Antiga', 'slug' => 'antiga']);

        $response = $this->put('/admin/categories/'.$category->id, [
            'name' => 'Atualizada',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('blog_categories', ['name' => 'Atualizada']);
    }

    /** @test */
    public function admin_categories_can_delete_category()
    {
        $this->actingAsAdmin();

        $category = Category::create(['name' => 'Remover', 'slug' => 'remover']);

        $response = $this->delete('/admin/categories/'.$category->id);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('blog_categories', ['id' => $category->id]);
    }
}
