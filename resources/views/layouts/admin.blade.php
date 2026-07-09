<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - '.config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-light">

<div class="d-flex w-100">
    <aside class="d-flex flex-column flex-shrink-0 p-3 bg-dark" style="width: 240px; min-height: 100vh;">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 text-white text-decoration-none">
            <span class="fs-4">&rsaquo; Admin</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('admin.examples.index') }}" class="nav-link text-white">Examples</a>
            </li>
            <li>
                <a href="{{ route('home') }}" class="nav-link text-white">Voltar ao site</a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <div class="me-2">Admin</div>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small">
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <main class="flex-grow-1 p-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@vite('resources/js/app.js')
</body>
</html>
