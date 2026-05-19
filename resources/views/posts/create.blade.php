@extends('layouts.app')

@section('content')
<div class="header">
    <div class="logo">
        <h1>Create New Post</h1>
    
    </div>
    <div class="nav-links">
        <a href="{{ route('posts.index') }}" class="btn btn-outline">← Back to Posts</a>
    </div>
</div>

<div class="content">
    <form method="POST" action="{{ route('posts.store') }}" style="max-width: 800px; margin: 0 auto;">
        @csrf

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Post Title</label>
            <input type="text" 
                   name="title" 
                   value="{{ old('title') }}"
                   placeholder="Enter an amazing title..."
                   style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; transition: 0.3s;">
            @error('title')
                <p style="color: #e74c3c; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Post Content</label>
            <textarea name="content" 
                      rows="8"
                      placeholder="Write your post content here..."
                      style="width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; resize: vertical; font-family: inherit;">{{ old('content') }}</textarea>
            @error('content')
                <p style="color: #e74c3c; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('posts.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Publish Post</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    input:focus, textarea:focus {
        outline: none;
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
</style>
@endpush