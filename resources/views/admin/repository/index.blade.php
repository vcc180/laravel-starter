@extends('layouts.admin')

@section('title', 'Repositório')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Repositório</h1>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(empty($packages))
            <p class="text-muted">Nenhum pacote cadastrado ainda.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead><tr><th>Tipo</th><th>Nome</th><th>Slug</th><th>Versão</th><th class="text-end">Ações</th></tr></thead>
                    <tbody>
                        @foreach($packages as $package)
                            @php
                                $installed = isset($installed[$package['type'].'/'.$package['slug']]);
                            @endphp
                            <tr>
                                <td>{{ ucfirst($package['type']) }}</td>
                                <td>{{ $package['name'] }}</td>
                                <td><code>{{ $package['slug'] }}</code></td>
                                <td>{{ $package['version'] ?? '-' }}</td>
                                <td class="text-end">
                                    @if($installed)
                                        <form class="d-inline" method="post" action="{{ route('admin.repository.uninstall', ['type'=>$package['type'],'slug'=>$package['slug']]) }}" onsubmit="return confirm('Remover instalação?')" >
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Remover</button>
                                        </form>
                                    @else
                                        <form class="d-inline" method="post" action="{{ route('admin.repository.install', ['type'=>$package['type'],'slug'=>$package['slug']]) }}" >
                                            @csrf
                                            <button class="btn btn-sm btn-primary">Instalar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
