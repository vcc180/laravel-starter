@extends('layouts.admin-auth')

@section('title', 'Login admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header"><h1 class="h5 mb-0">Login admin</h1></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-dark w-100" type="submit">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
