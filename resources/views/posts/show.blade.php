@extends('layouts.app')

@section('content')
<div class="header">
    <div class="logo">
        <h1>📖 {{ $post->title }}</h1>
        <p>Posted on {{ $post->created_at->format('F j, Y') }}</p>
    </div>
    <div class="nav-links">
        <a href="{{ route('posts.index') }}" class="btn btn-outline">← All Posts</a>
    </div>
</div>

<div class="content">
    <div style="background: #f8f9fa; border-radius: 15px; padding: 30px;">
        <div style="font-size: 18px; line-height: 1.8; color: #444; margin-bottom: 30px;">
            {{ $post->content }}
        </div>

        <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
            <div style="display: flex; gap: 30px; align-items: center; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 24px; font-weight: bold; color: #f39c12;">{{ $post->averageRating }}</span>
                    <span style="color: #666;">/5</span>
                </div>
                <div style="color: #666;">
                    📊 {{ $post->ratings->count() }} total ratings
                </div>
            </div>
        </div>
    </div>
</div>
@endsection