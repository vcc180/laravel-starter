<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    protected string $viewPath = 'admin.articles';
    protected string $routePrefix = 'admin.articles';
    protected array $rulesStore = [];
    protected array $rulesUpdate = [];

    protected function modelClass(): string
    {
        return Article::class;
    }

    protected function rulesStore(): array
    {
        return (new ArticleRequest())->rules();
    }

    protected function rulesUpdate(): array
    {
        return (new ArticleRequest())->rules();
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function show(Article $article)
    {
        return view('admin.articles.show', ['item' => $article]);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', ['item' => $article]);
    }

    protected function searchable(): string
    {
        return 'title';
    }

    protected function applySearch($query, string $search): void
    {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%");
    }

    protected function validationAttributeMessages(): array
    {
        return [
            'title.required' => 'Informe o título.',
            'body.required' => 'Informe o conteúdo.',
        ];
    }
}
