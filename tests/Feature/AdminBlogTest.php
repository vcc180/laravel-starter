<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;

class AdminBlogTest extends TestCase
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
    public function admin_blog_index_loads()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/blog');

        $response->assertStatus(200);
        $response->assertSee('Novo post');
    }

    /** @test */
    public function admin_blog_can_create_post()
    {
        $this->actingAsAdmin();

        $category = Category::create(['name' => 'Geral', 'slug' => 'geral']);

        $response = $this->post('/admin/blog', [
            'title' => 'Post admin',
            'slug' => 'post-admin',
            'body' => 'Corpo do post.',
            'blog_category_id' => $category->id,
            'is_published' => 1,
        ]);

        $response->assertRedirect('/admin/blog');
        $this->assertDatabaseHas('blog_posts', ['slug' => 'post-admin']);
    }

    /** @test */
    public function admin_blog_can_update_post()
    {
        $this->actingAsAdmin();

        $category = Category::create(['name' => 'Geral', 'slug' => 'geral']);
        $post = Post::create([
            'title' => 'Antigo',
            'slug' => 'antigo',
            'body' => 'Corpo antigo.',
            'blog_category_id' => $category->id,
            'is_published' => 1,
        ]);

        $response = $this->put('/admin/blog/'.$post->id, [
            'title' => 'Atualizado',
            'slug' => 'atualizado',
            'body' => 'Corpo atualizado.',
            'blog_category_id' => $category->id,
            'is_published' => 1,
        ]);

        $response->assertRedirect('/admin/blog');
        $this->assertDatabaseHas('blog_posts', ['slug' => 'atualizado']);
    }

    /** @test */
    public function admin_blog_can_delete_post()
    {
        $this->actingAsAdmin();

        $category = Category::create(['name' => 'Geral', 'slug' => 'geral']);
        $post = Post::create([
            'title' => 'Remover',
            'slug' => 'remover',
            'body' => 'Corpo.',
            'blog_category_id' => $category->id,
            'is_published' => 1,
        ]);

        $response = $this->delete('/admin/blog/'.$post->id);

        $response->assertRedirect('/admin/blog');
        $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
    }
}
