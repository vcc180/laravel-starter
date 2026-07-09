@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Detalhe</h1></div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">ID</dt><dd class="col-sm-9">{{ $example->id }}</dd>
            <dt class="col-sm-3">Nome</dt><dd class="col-sm-9">{{ $example->name }}</dd>
            <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $example->notes }}</dd>
        </dl>
        <a class="btn btn-secondary" href="{{ route('admin.examples.index') }}">Voltar</a>
    </div>
</div>
@endsection
