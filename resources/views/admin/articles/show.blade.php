@extends('layouts.admin')

@section('title', 'Article')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Article</h1>
        <div class="btn-group">
            <a href="{{ route('admin.articles.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted mb-1">Título</p>
        <p>{{ $item->title }}</p>
        <p class="text-muted mb-1 mt-3">Conteúdo</p>
        <p>{{ $item->body ?: '—' }}</p>
        <p class="text-muted mb-1 mt-3">Ativo</p>
        <p>{{ $item->is_active ? 'Sim' : 'Não' }}</p>
    </div>
</div>
@endsection
