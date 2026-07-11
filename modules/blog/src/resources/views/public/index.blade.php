@extends(isset($themeLayout) ? $themeLayout : 'layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Blog</h1>

    @if(empty($posts))
        <p class="text-muted">Nenhum post publicado.</p>
    @else
        @foreach($posts as $post)
            <article class="mb-4 pb-3 border-bottom">
                <h2 class="h5">
                    <a href="{{ route('blog.posts.show', $post) }}" class="text-decoration-none">
                        {{ $post->title }}
                    </a>
                </h2>
                <div class="small text-muted">
                    {{ optional($post->category)->name ?? 'Sem categoria' }}
                </div>
                <p class="mb-0">{{ \Illuminate\Support\Str::limit($post->body, 160) }}</p>
            </article>
        @endforeach
    @endif
</div>
@endsection
