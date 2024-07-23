<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @group Blog
 *
 * @unauthenticated
 */
class PostController extends ApiController
{
    /**
     * Posts List
     *
     * Display a listing of the posts.
     * 
     * @bodyParam page int
     * @bodyParam limit int
     */
    public function index(Request $request)
    {
        $limit = max(30, $request->get('limit', 20));

        $posts = Post::active()->with(['category'])->paginate($limit);

        return $this->successResponse(PostResource::collection($posts));
    }

    /**
     * Post Detail
     *
     * Display a detail of the post.
     *
     * @bodyParam page int
     * @bodyParam limit int
     */
    public function show(Request $request, $slugOrId)
    {
        $post = Post::active()->with(['category'])
            ->where(function ($q) use ($slugOrId) {
                $q->where('id', $slugOrId)->orWhere('slug', $slugOrId);
            })->first();

        return $this->successResponse(PostResource::make($post));
    }
}
