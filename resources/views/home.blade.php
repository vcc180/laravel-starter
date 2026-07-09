@extends('layouts.app')

@section('content')
<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">{{ config('app.name') }}</h1>
        <p class="col-md-8 fs-4">Starter Laravel com base model/admin/auth pronta para reuso.</p>
        <a class="btn btn-dark btn-lg mt-3" href="{{ url('/admin') }}">Ir para admin</a>
    </div>
</div>
@endsection
