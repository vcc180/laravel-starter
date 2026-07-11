<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $query = Article::query()
            ->when($request->filled('q'), function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(20);

        return view('admin.articles.index', [
            'items' => $query,
            'term' => (string) $request->query('q', ''),
        ]);
    }

    public function store(ArticleRequest $request)
    {
        Article::create($request->validated());

        return redirect()->route('admin.articles.index')->with('status', 'Registro criado.');
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->update($request->validated());

        return redirect()->route('admin.articles.index')->with('status', 'Registro atualizado.');
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
