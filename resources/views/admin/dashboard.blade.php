@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Dashboard</h1></div>
    <div class="card-body">
        <p>Base admin funcionando. Use os módulos para continuar.</p>
        <a class="btn btn-primary" href="{{ route('admin.examples.index') }}">Exemplo CRUD</a>
    </div>
</div>
@endsection
