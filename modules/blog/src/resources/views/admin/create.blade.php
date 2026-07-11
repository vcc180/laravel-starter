@extends('layouts.admin')

@section('title', 'Novo post')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Novo post</h1>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.blog.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input class="form-control" name="title" value="{{ old('title') }}">
                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" value="{{ old('slug') }}">
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="blog_category_id" class="form-select">
                    <option value="">Sem categoria</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" @selected(old('blog_category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('blog_category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Conteúdo</label>
                <textarea class="form-control" name="body" rows="6">{{ old('body') }}</textarea>
                @error('body')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published'))>
                <label class="form-check-label" for="is_published">Publicado</label>
            </div>
            <button class="btn btn-primary" type="submit">Criar</button>
            <a class="btn btn-link" href="{{ route('admin.blog.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
