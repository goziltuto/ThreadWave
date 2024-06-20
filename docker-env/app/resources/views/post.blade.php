@extends('layouts.layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- 投稿タイトル -->
<h1 class="text-center">
    <span class="txt-rotate" data-period="2000" data-rotate='[ "{{ $post->title }}" ]'></span>
</h1>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    コメント一覧
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($comments as $comment)
                        <li class="list-group-item">
                            <div id="display-comment-{{ $comment->id }}">
                                @php
                                $nameColor = 'text-success'; // デフォルトの色
                                if ($comment->user->id == 0) {
                                $nameColor = 'text-danger'; // 管理者は赤色
                                } elseif ($comment->user_id == $post->user_id) {
                                $nameColor = 'text-primary'; // 投稿者は青色
                                }
                                @endphp

                                <strong class="{{ $nameColor }}">{{ $loop->iteration }}. 名前: {{ $comment->user->name }} </strong>{{ $comment->created_at->format('Y/m/d') }}({{ $comment->created_at->isoFormat('ddd') }}) {{ $comment->created_at->format('H:i:s') }} ID: {{ $comment->user_id }}
                                <br><span class="comment-text {{ $comment->deleted_at ? 'text-secondary' : '' }}">{{ $comment->deleted_at ? 'このコメントは削除されました' : $comment->comment }}</span>
                                <br>
                                @if ($comment->isOwner || Auth::user()->id === 0) <!-- 自分のコメントかまたは管理者かを確認 -->
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    @if (is_null($comment->deleted_at))
                                    <a href="#" class="btn btn-link edit-comment" data-id="{{ $comment->id }}">編集</a>
                                    <a href="#" class="btn btn-link delete-comment text-danger" data-id="{{ $comment->id }}">削除</a>
                                    @endif
                                    <a href="#" class="btn btn-link restore-comment text-success" data-id="{{ $comment->id }}" style="display: none;">復元</a>
                                </div>
                                @endif
                            </div>
                            <div id="edit-comment-form-{{ $comment->id }}" style="display: none;">
                                <textarea class="form-control" name="edited-comment" required>{{ $comment->comment }}</textarea>
                                <button type="button" class="btn btn-primary update-comment" data-id="{{ $comment->id }}">更新</button>
                                <button type="button" class="btn btn-secondary cancel-edit" data-id="{{ $comment->id }}">キャンセル</button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <form method="POST" action="{{ route('comments.store') }}" id="comment-form">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="コメントを入力" required></textarea>
                            <div id="charCount"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="comment-submit-btn">
                            <span id="comment-btn-text">送信</span>
                            <div class="spinner-border spinner-border-sm text-white d-none" role="status" id="comment-spinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection