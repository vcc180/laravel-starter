@extends('layouts.admin')

@section('title', 'Blog')

@section('content')
<div class="row g-3">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="text-muted">Posts</div>
        <div class="display-6">{{ $stats['posts'] }}</div>
        <div class="small text-muted">{{ $stats['published'] }} publicados · {{ $stats['draft'] }} rascunhos</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="text-muted">Categorias</div>
        <div class="display-6">{{ $stats['categories'] }}</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="text-muted">Tags</div>
        <div class="display-6">{{ $stats['tags'] }}</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="text-muted">Último post</div>
        <div class="fs-5">{{ $stats['last_post_at'] ? \Carbon\Carbon::parse($stats['last_post_at'])->diffForHumans() : '—' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
