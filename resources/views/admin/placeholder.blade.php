@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">Admin</div>
    <div class="card-body">
        <p class="mb-0">Base admin do starter. Use para montar o primeiro módulo.</p>
        <a class="btn btn-secondary mt-3" href="{{ route('home') }}">Voltar</a>
    </div>
</div>
@endsection
