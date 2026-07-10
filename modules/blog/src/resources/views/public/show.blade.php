@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">{{ $post->title }}</h1>
    <div class="small text-muted mb-3">
        {{ optional($post->category)->name ?? 'Sem categoria' }}
    </div>
    <p class="whitespace-pre-line">{{ $post->body }}</p>
    <a class="btn btn-link px-0" href="{{ route('blog.index') }}">&larr; Voltar</a>
</div>
@endsection
