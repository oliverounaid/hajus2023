<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog', [
            'posts' => Blog::with('comments')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Auth/Blog/Create');
    }

    public function store(Request $request)
    {
        Blog::create($request->validate([
            'title' => 'required',
            'description' => 'required',
        ]));
        return redirect()->route('blog.index');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Auth/Blog/Edit', [
            'post' => $blog
        ]);
    }

    public function update(Blog $blog, Request $request)
    {
        $blog->update($request->validate([
            'title' => 'required',
            'description' => 'required',
        ]));
        return redirect()->back();
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->back();
    }

    public function show() {
        return Inertia::render('Auth/Blog/Index', [
            'posts' => Blog::all()
        ]);
    }
}
