<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends AdminController
{
    protected string $viewPath = 'admin.articles';
    protected string $routePrefix = 'admin.articles';
    protected string $modelClass = Article::class;
    protected array $validateStore = [
        'title' => ['required', 'string', 'max:255'],
        'body' => ['nullable', 'string'],
        'is_active' => ['sometimes', 'boolean'],
    ];
    protected array $validateUpdate = [
        'title' => ['required', 'string', 'max:255'],
        'body' => ['nullable', 'string'],
        'is_active' => ['sometimes', 'boolean'],
    ];

    protected function modelClass(): string
    {
        return Article::class;
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
