@extends('layouts.admin')

@section('title', 'Tags')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-primary">Nova</a>
    </div>
    <div class="card-body">
        @include('blog::admin.tags.table')
    </div>
</div>
@endsection
