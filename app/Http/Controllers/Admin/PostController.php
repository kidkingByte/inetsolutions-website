<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post]);
    }

    public function store(Request $request)
    {
        Post::create($this->validated($request));

        return redirect()->route('admin.posts.index')->with('status', 'Post created.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request, $post));

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('status', 'Post deleted.');
    }

    protected function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Keep the slug stable after creation so published URLs never break.
        $data['slug'] = $post?->slug ?? $this->uniqueSlug($request->input('title'));
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published']
            ? ($post?->published_at ?? now())
            : null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        } else {
            unset($data['cover_image']);
        }

        return $data;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;

        for ($i = 2; Post::withTrashed()->where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
