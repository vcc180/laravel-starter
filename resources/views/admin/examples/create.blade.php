@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Novo</h1></div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.examples.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" value="{{ old('name') }}">
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes">{{ old('notes') }}</textarea>
                @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <a class="btn btn-secondary" href="{{ route('admin.examples.index') }}">Voltar</a>
            <button class="btn btn-primary" type="submit">Salvar</button>
        </form>
    </div>
</div>
@endsection
