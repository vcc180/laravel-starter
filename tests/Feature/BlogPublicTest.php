<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;

class BlogPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function public_blog_index_lists_published_posts()
    {
        $category = Category::create(['name' => 'Geral', 'slug' => 'geral']);
        Post::create([
            'title' => 'Post público',
            'slug' => 'post-publico',
            'body' => 'Conteúdo público.',
            'blog_category_id' => $category->id,
            'is_published' => true,
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Post público');
    }

    /** @test */
    public function public_blog_show_displays_published_post()
    {
        $category = Category::create(['name' => 'Geral', 'slug' => 'geral']);
        $post = Post::create([
            'title' => 'Post detalhe',
            'slug' => 'post-detalhe',
            'body' => 'Conteúdo detalhe.',
            'blog_category_id' => $category->id,
            'is_published' => true,
        ]);

        $response = $this->get('/blog/'.$post->slug);

        $response->assertStatus(200);
        $response->assertSee('Post detalhe');
    }
}
