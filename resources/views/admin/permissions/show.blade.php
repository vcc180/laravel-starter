@extends('layouts.admin')

@section('title', 'Permissão #'.$item->id)

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Permissão #{{ $item->id }}</h1></div>
    <div class="card-body">
        <p><strong>Nome:</strong> {{ $item->name }}</p>
        <p><strong>Rótulo:</strong> {{ $item->label }}</p>
        <a href="{{ route('admin.permissions.edit', $item) }}" class="btn btn-primary">Editar</a>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>
@endsection
