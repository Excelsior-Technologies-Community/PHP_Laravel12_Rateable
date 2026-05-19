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





    <h2 style="margin-bottom: 25px; color: #333;">All Posts</h2>

    @forelse($posts as $post)
    <div style="background: #f8f9fa; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
            <h3 style="color: #2c3e50; font-size: 22px;">{{ $post->title }}</h3>
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
                    <span style="font-size: 28px; font-weight: bold; color: #f39c12;">{{ $post->averageRating }}</span>
                    <span style="color: #666;">/5</span>
                </div>
                <div>
                    <div style="color: #666;">Based on {{ $post->ratings->count() }} ratings</div>
                </div>
            </div>

            <!-- Rating Form -->
            <form method="POST" action="{{ route('posts.rate', $post->id) }}" id="rate-form-{{ $post->id }}">
                @csrf
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <div class="stars" style="direction: rtl; display: flex; gap: 5px;">
                        @for($i = 5; $i >= 1; $i--)
                        <label for="star{{ $i }}_{{ $post->id }}" style="font-size: 35px; cursor: pointer; color: #ddd; transition: 0.2s;" 
                               onmouseover="this.style.color='#f1c40f'" 
                               onmouseout="this.style.color='#ddd'"
                               onclick="document.getElementById('star{{ $i }}_{{ $post->id }}').checked = true; document.getElementById('rate-form-{{ $post->id }}').submit();">
                            ★
                        </label>
                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}_{{ $post->id }}" style="display: none;">
                        @endfor
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Submit Rating</button>
                </div>
            </form>

           
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 50px; color: #999;">
      
      
    </div>
    @endforelse

    <!-- Pagination -->
    <div style="margin-top: 30px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .stars label:hover,
    .stars label:hover ~ label {
        color: #f1c40f !important;
    }
    
    .stars input:checked ~ label {
        color: #f1c40f !important;
    }
    
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