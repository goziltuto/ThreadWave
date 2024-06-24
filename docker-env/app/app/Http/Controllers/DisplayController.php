<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DisplayController extends Controller
{
    public function getPostsByCategory($categoryId)
    {
        // カテゴリを取得
        $posts = Post::where('category_id', $categoryId)->get();
        return response()->json($posts);
    }
}
