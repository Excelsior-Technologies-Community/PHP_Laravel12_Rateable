@extends('layouts.app')

@section('content')
<div class="header">
    <div class="logo">
        <h1>Rateable Posts</h1>
        <p>Rate and review amazing content</p>
    </div>
    <div class="nav-links">
        <a href="{{ route('posts.create') }}" class="btn btn-primary"> Create Post</a>
    </div>
</div>

<!-- Filter / Sort Bar -->
<div style="display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap;">
    <a href="{{ route('posts.index') }}" class="btn btn-outline">All</a>
    <a href="{{ route('posts.index', ['sort' => 'top_rated']) }}" class="btn btn-outline">🔥 Top Rated</a>
    @for($s = 5; $s >= 1; $s--)
        <a href="{{ route('posts.index', ['star' => $s]) }}" class="btn btn-outline">{{ $s }}★ Only</a>
    @endfor
</div>

<h2 style="margin-bottom: 25px; color: #333;">All Posts</h2>

@forelse($posts as $post)
<div style="background: #f8f9fa; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
        <h3 style="color: #2c3e50; font-size: 22px;">
            <a href="{{ route('posts.show', $post->id) }}" style="color: #2c3e50; text-decoration: none;">{{ $post->title }}</a>
        </h3>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" style="padding: 5px 12px; font-size: 12px;">Delete</button>
        </form>
    </div>

    <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">{{ $post->content }}</p>

    <!-- Rating Section -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-top: 10px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
            <div>
                <span class="avg-rating-{{ $post->id }}" style="font-size: 28px; font-weight: bold; color: #f39c12;">{{ $post->averageRating }}</span>
                <span style="color: #666;">/5</span>
            </div>
            <div>
                <div class="total-ratings-{{ $post->id }}" style="color: #666;">Based on {{ $post->ratings->count() }} ratings</div>
            </div>
        </div>

        <!-- AJAX Rating Form -->
        <form class="ajax-rate-form" data-post-id="{{ $post->id }}" data-url="{{ route('posts.rate', $post->id) }}">
            @csrf
            <div class="stars" data-post-id="{{ $post->id }}" style="display: flex; gap: 5px; margin-bottom: 15px;">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-star star-icon" data-value="{{ $i }}" style="font-size: 30px; cursor: pointer; color: #ddd;">★</i>
                @endfor
                <input type="hidden" name="rating" class="rating-value" value="0">
            </div>

            <div style="margin-bottom: 12px;">
                <input type="text" name="review_title" placeholder="Review title (optional)"
                       style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 12px;">
                <textarea name="review_text" rows="3" placeholder="Write your review (optional)..."
                          style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Submit Rating</button>
            <span class="rate-status" style="margin-left: 10px; font-size: 13px; color: #27ae60;"></span>
        </form>

        <!-- Existing Reviews -->
        @if($post->ratings->where('review_text', '!=', null)->count())
        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
            @foreach($post->ratings->where('review_text', '!=', null) as $review)
                <div style="margin-bottom: 12px;">
                    <strong>{{ $review->review_title ?? 'Anonymous review' }}</strong>
                    <span style="color: #f39c12;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    <p style="color: #666; margin: 4px 0 0;">{{ $review->review_text }}</p>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@empty
<div style="text-align: center; padding: 50px; color: #999;">
    No posts found.
</div>
@endforelse

<!-- Pagination -->
<div style="margin-top: 30px;">
    {{ $posts->links() }}
</div>

@endsection

@push('styles')
<style>
    .star-icon { transition: 0.2s; }
    .star-icon.active,
    .star-icon:hover { color: #f1c40f !important; }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        list-style: none;
    }

    .pagination a, .pagination span {
        padding: 8px 14px;
        background: #f0f0f0;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
    }

    .pagination .active span {
        background: #667eea;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Star click -> set hidden value + highlight
    document.querySelectorAll('.stars').forEach(function (starGroup) {
        const stars = starGroup.querySelectorAll('.star-icon');
        const hiddenInput = starGroup.querySelector('.rating-value');

        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                const value = parseInt(star.dataset.value);
                hiddenInput.value = value;

                stars.forEach(function (s) {
                    s.classList.toggle('active', parseInt(s.dataset.value) <= value);
                });
            });
        });
    });

    // AJAX form submit (no page refresh)
    document.querySelectorAll('.ajax-rate-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const postId = form.dataset.postId;
            const url = form.dataset.url;
            const statusEl = form.querySelector('.rate-status');
            const formData = new FormData(form);

            if (formData.get('rating') === '0') {
                statusEl.style.color = '#e74c3c';
                statusEl.textContent = 'Please select a star rating first';
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.success) {
                    statusEl.style.color = '#27ae60';
                    statusEl.textContent = data.message;

                    document.querySelector('.avg-rating-' + postId).textContent = data.average_rating;
                    document.querySelector('.total-ratings-' + postId).textContent = 'Based on ' + data.total_ratings + ' ratings';
                } else {
                    statusEl.style.color = '#e74c3c';
                    statusEl.textContent = 'Something went wrong. Try again.';
                }
            })
            .catch(function () {
                statusEl.style.color = '#e74c3c';
                statusEl.textContent = 'Error submitting rating.';
            });
        });
    });

});
</script>
@endpush