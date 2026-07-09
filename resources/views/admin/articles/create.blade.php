@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Novo article</h1></div>
    <div class="card-body">
        <form action="{{ route('admin.articles.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                @error('title') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Conteúdo</label>
                <textarea name="body" rows="5" class="form-control">{{ old('body') }}</textarea>
                @error('body') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Ativo</label>
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
