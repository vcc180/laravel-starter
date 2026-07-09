<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;

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

    protected function validationAttributeMessages(): array
    {
        return [
            'title.required' => 'Informe o título.',
            'body.required' => 'Informe o conteúdo.',
        ];
    }
}
