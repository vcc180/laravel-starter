@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="post" action="{{ route('admin.locales.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Código</label>
                <input name="locale" class="form-control" value="{{ old('locale') }}" required maxlength="8">
            </div>
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3 form-check">
                <input class="form-check-input" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label">Ativo</label>
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a class="btn btn-link" href="{{ route('admin.locales.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
