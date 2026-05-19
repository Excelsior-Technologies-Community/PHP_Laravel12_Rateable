<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Display all posts with ratings
    public function index()
    {
        $posts = Post::with('ratings')->latest()->paginate(5);
        
        // Get top 3 rated posts
        $topPosts = Post::with('ratings')
            ->get()
            ->sortByDesc(fn($post) => $post->averageRating)
            ->take(3);

        // Get overall statistics
        $stats = [
            'total_posts' => Post::count(),
            'total_ratings' => Rating::count(),
            'avg_all_ratings' => round(Rating::avg('rating') ?: 0, 1),
        ];

        return view('posts.index', compact('posts', 'topPosts', 'stats'));
    }

    // Show create post form
    public function create()
    {
        return view('posts.create');
    }

    // Store new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10'
        ], [
            'title.required' => 'Please enter a post title',
            'title.min' => 'Title must be at least 3 characters',
            'content.required' => 'Please enter post content',
            'content.min' => 'Content must be at least 10 characters'
        ]);

        Post::create($validated);

        return redirect('/')->with('success', '✨ Post created successfully!');
    }

    // Handle rating submission
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ], [
            'rating.required' => 'Please select a rating',
            'rating.in' => 'Rating must be between 1 and 5 stars'
        ]);

        $post = Post::findOrFail($id);
        
        // Get user ID (using session or random for demo)
        $userId = session('user_id', rand(1, 10000));
        session(['user_id' => $userId]);

        // Check if user already rated
        $existingRating = $post->ratings()->where('user_id', $userId)->first();
        
        if ($existingRating) {
            // Update existing rating
            $existingRating->update(['rating' => $request->rating]);
            $message = '🔄 Rating updated successfully!';
        } else {
            // Create new rating
            $post->ratings()->create([
                'user_id' => $userId,
                'rating' => $request->rating
            ]);
            $message = '⭐ Rating submitted successfully!';
        }

        return back()->with('success', $message);
    }

    // Show single post
    public function show($id)
    {
        $post = Post::with('ratings')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    // Delete post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->ratings()->delete();
        $post->delete();
        
        return redirect('/')->with('success', '🗑️ Post deleted successfully!');
    }
}