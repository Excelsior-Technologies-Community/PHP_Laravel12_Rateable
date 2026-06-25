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

        <!-- Reviews list -->
        @if($post->ratings->where('review_text', '!=', null)->count())
        <div style="margin-top: 30px; border-top: 1px solid #e0e0e0; padding-top: 20px;">
            <h3 style="margin-bottom: 15px; color: #333;">Reviews</h3>
            @foreach($post->ratings->where('review_text', '!=', null) as $review)
                <div style="background: white; border-radius: 10px; padding: 15px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>{{ $review->review_title ?? 'Anonymous review' }}</strong>
                        <span style="color: #f39c12;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    <p style="color: #666; margin: 6px 0 0;">{{ $review->review_text }}</p>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection