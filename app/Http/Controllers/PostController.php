<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Display all posts with ratings + filter support
    public function index(Request $request)
    {
        $query = Post::with('ratings')->latest();

        // Filter by star rating (e.g. ?star=5 or ?star=1)
        if ($request->filled('star')) {
            $star = (int) $request->star;
            $query->whereHas('ratings', function ($q) use ($star) {
                $q->where('rating', $star);
            });
        }

        // Filter: top rated posts (sort by average rating)
        if ($request->filled('sort') && $request->sort === 'top_rated') {
            $posts = $query->get()->sortByDesc(fn($post) => $post->averageRating);
            $posts = new \Illuminate\Pagination\LengthAwarePaginator(
                $posts->forPage($request->get('page', 1), 5),
                $posts->count(),
                5,
                $request->get('page', 1),
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $posts = $query->paginate(5)->appends($request->query());
        }

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

    // Handle rating submission (supports normal + AJAX request)
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_title' => 'nullable|string|max:255',
            'review_text' => 'nullable|string|max:2000',
        ], [
            'rating.required' => 'Please select a rating',
            'rating.integer' => 'Rating must be between 1 and 5 stars',
        ]);

        $post = Post::findOrFail($id);

        // Get user ID (using session or random for demo)
        $userId = session('user_id', rand(1, 10000));
        session(['user_id' => $userId]);

        // Check if user already rated
        $existingRating = $post->ratings()->where('user_id', $userId)->first();

        $data = [
            'rating' => $request->rating,
            'review_title' => $request->review_title,
            'review_text' => $request->review_text,
        ];

        if ($existingRating) {
            // Update existing rating
            $existingRating->update($data);
            $message = '🔄 Rating updated successfully!';
        } else {
            // Create new rating
            $post->ratings()->create(array_merge($data, ['user_id' => $userId]));
            $message = '⭐ Rating submitted successfully!';
        }

        // AJAX request -> return JSON, no page refresh
        if ($request->ajax() || $request->wantsJson()) {
            $post->refresh();
            return response()->json([
                'success' => true,
                'message' => $message,
                'average_rating' => $post->averageRating,
                'total_ratings' => $post->ratings->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    // Show single post (with all reviews)
    public function show($id)
    {
        $post = Post::with('ratings.user')->findOrFail($id);
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