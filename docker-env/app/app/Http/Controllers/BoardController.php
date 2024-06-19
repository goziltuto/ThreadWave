<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class BoardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // データベースから投稿データを取得
        $posts = Post::orderBy('created_at', 'desc')->get();

        $mostCommentedPosts = Post::withCount('comments')->orderBy('comments_count', 'desc')->get();

        return view('home', ['posts' => $posts, 'mostCommentedPosts' => $mostCommentedPosts]);
    }

    /*/以下投稿処理/*/
    public function store(Request $request)
    {
        // フォームから送信されたデータをバリデート
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // 新しい投稿を作成しデータベースに保存
        Post::create([
            'user_id' => Auth::id(), // 現在ログインしているユーザーIDを使用
            'title' => $request->title,
        ]);

        // 投稿後、ホームページにリダイレクト
        return redirect('/');
    }

    /*/以下コメント処理/*/
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $comments = Comment::withTrashed()->where('post_id', $id)->orderBy('created_at', 'asc')->get();

        // コメントとログインユーザーの所有者を比較して、自分の投稿であれば編集と削除ボタンを表示するためのフラグをセットする
        $comments = $comments->map(function ($comment) {
            $comment->isOwner = ($comment->user_id === Auth::id());
            return $comment;
        });

        return view('post', ['post' => $post, 'comments' => $comments]);
    }

    public function store2(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:255',
        ]);

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(), // 現在ログインしているユーザーIDを使用
            'comment' => $request->comment,
        ]);

        return redirect()->route('post.show', $request->post_id);
    }
    /**以下コメント編集処理**/
    public function update(Request $request, $id)
    {
        // \Log::info('update メソッドが呼び出されました。');

        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $comment = Comment::find($id);

        // ユーザーがコメントの所有者であることを確認
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json(['message' => 'Comment updated successfully']);
    }

    /*以下コメント削除処理*/
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['success' => true]);
    }
    // コメント復元処理
    public function restore($id)
    {
        $comment = Comment::withTrashed()->find($id);
        if ($comment) {
            $comment->restore();
            return response()->json(['commentText' => $comment->text], 200);
        } else {
            return response()->json(['error' => 'コメントが見つかりません'], 404);
        }
    }

    // プロフィール表示メソッドを追加
    public function showProfile()
    {
        $user = Auth::user();
        $allUsers = User::withTrashed()->get(); // 全ユーザを取得（論理削除されたユーザも含む）

        return view('profile', compact('user', 'allUsers'));
    }
    // プロフィールからパスワード変更
    public function name_update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('username');
        $user->save();

        return redirect('/')->with('status', 'プロフィールが更新されました');
    }


    // 検索機能を追加
    public function search(Request $request)
    {
        // 検索キーワードを取得
        $searchText = $request->input('search_text');

        // 投稿を検索（例として、タイトルが検索キーワードに部分一致するものを取得）
        $results = Post::where('title', 'like', '%' . $searchText . '%')->get();

        return $results;
    }

    public function password_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 現在のパスワードが一致しているか確認
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', '現在のパスワードが間違っています');
        }

        // 新しいパスワードと確認用パスワードが一致しているか確認
        if ($request->input('new_password') !== $request->input('password_confirmation')) {
            return redirect()->back()->with('error', '新しいパスワードと確認用パスワードが一致しません');
        }

        // パスワードを更新
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect('/')->with('status', 'パスワードが更新されました');
    }
}
