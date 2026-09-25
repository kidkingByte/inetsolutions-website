<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->latest('published_at');

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Post::published()->distinct()->pluck('category')->filter()->values();

        return view('site.news.index', compact('posts', 'categories', 'category'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->increment('views');

        $related = Post::published()->where('id', '!=', $post->id)
            ->where('category', $post->category)->latest('published_at')->take(3)->get();

        return view('site.news.show', compact('post', 'related'));
    }
}
