@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="h6">Idioma: {{ $item->locale }}</h3>
        <p class="mb-1"><strong>Nome:</strong> {{ $item->name }}</p>
        <p class="mb-1"><strong>Status:</strong> {{ $item->is_active ? 'Ativo' : 'Inativo' }}</p>
        <a class="btn btn-secondary" href="{{ route('admin.locales.index') }}">Voltar</a>
    </div>
</div>
@endsection
