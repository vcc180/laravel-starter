@extends('layouts.admin')

@section('title', 'Editar permissão #'.$item->id)

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Editar permissão #{{ $item->id }}</h1></div>
    <div class="card-body">
        <form action="{{ route('admin.permissions.update', $item) }}" method="post">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" class="form-control" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Rótulo</label>
                <input type="text" name="label" value="{{ old('label', $item->label) }}" class="form-control">
                @error('label') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
