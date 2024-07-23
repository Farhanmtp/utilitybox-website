<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::active()->paginate(9);

        $latestPosts = Post::active()->orderBy('created_at', 'DESC')->limit(6)->get();

        return Inertia::render('Blog/Blog', [
            'posts' => (new PostCollection($posts))->toArray($request),
            'latestPosts' => (new PostCollection($latestPosts))->toArray($request)
        ]);
    }

    public function show(Request $request, $slug)
    {

        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            abort(404);
        }

        $blog = new PostResource($post);

        $relativePosts = Post::where('category_id', $post->category_id)->limit(10)->get();

        return Inertia::render('Blog/BlogDetails', [
            'blog' => $blog->toArray($request),
            'relativePosts' => PostResource::collection($relativePosts)->toArray($request)
        ]);
    }
}
