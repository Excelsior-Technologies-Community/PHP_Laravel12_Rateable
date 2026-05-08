<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use willvincent\Rateable\Rating;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('ratings')->latest()->get();

        // Top Rated Posts
        $topPosts = Post::with('ratings')
            ->get()
            ->sortByDesc(function ($post) {
                return $post->averageRating;
            })
            ->take(3);

        return view('posts.index', compact('posts', 'topPosts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect('/');
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $post = Post::findOrFail($id);

        $rating = new Rating();
        $rating->rating = $request->rating;
        $rating->user_id = rand(1, 9999);

        $post->ratings()->save($rating);

        return back()->with('success', 'Rating Submitted Successfully!');
    }
}