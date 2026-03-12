<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use willvincent\Rateable\Rating;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect('/');
    }

    public function rate(Request $request, $id)
    {
        $post = Post::find($id);

        $rating = new Rating();
        $rating->rating = $request->rating;
        $rating->user_id = 1;

        $post->ratings()->save($rating);

        return back();
    }

}