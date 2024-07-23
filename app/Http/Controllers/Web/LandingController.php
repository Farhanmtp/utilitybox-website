<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Inertia\Inertia;

class LandingController extends Controller
{

    public function index()
    {
        $posts = Post::with(['category'])->latest()->limit(8)->get();
        return Inertia::render('Landing', [
            'latestPosts' => $posts->toArray()
        ]);
    }
}
