<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class BoardController extends Controller
{
    public function index()
    {
        // 投稿一覧をダミーデータとして渡す（実際にはデータベースから取得するなど）
        $posts = [
            ['name' => 'ユーザー1', 'title' => 'これはテスト投稿です。'],
            ['name' => 'ユーザー2', 'title' => '別のテスト投稿です。'],
        ];

        $posts = Post::all();
        return view('home', ['posts' => $posts]);
    }
}