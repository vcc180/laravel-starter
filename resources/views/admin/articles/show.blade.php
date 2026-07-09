@extends('layouts.admin')

@section('title', 'Article #'.$item->id ?? 'Article')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Article #{{ $item->id }}</h1></div>
    <div class="card-body">
        <p><strong>Título:</strong> {{ $item->title }}</p>
        <p><strong>Ativo:</strong> {{ $item->is_active ? 'Sim' : 'Não' }}</p>
        <p><strong>Conteúdo:</strong><br>{{ $item->body }}</p>
        <a href="{{ route('admin.articles.edit', $item) }}" class="btn btn-primary">Editar</a>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>
@endsection
