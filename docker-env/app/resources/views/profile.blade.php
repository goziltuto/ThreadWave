@extends('layouts.layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-md-offset-3 col-md-6">
            @if(Auth::check() && Auth::user()->id === 0)
            <!-- ログインしているのが管理者の場合 -->
            <h1 class="row justify-content-center p-5">管理者ページ</h1>
            <form action="{{ route('profile.update', Auth::id()) }}" method="POST">
                @csrf
                @method('PUT')
                <label for="username" class="col-form-label col-sm-10">ユーザ名：{{ $user->name }}</label>
                <label for="username" class="col-form-label col-sm-10">メールアドレス：{{ $user->email }}</label>
            </form>

            <h3 class="row justify-content-center p-4">管理者パスワード変更</h3>
            <div class="form-group">
                <label for="current-password">現在のパスワード</label>
                <input type="password" id="current-password" class="form-control" placeholder="現在のパスワードを入力">
            </div>
            <div class="form-group">
                <label for="new-password">新しいパスワード</label>
                <input type="password" id="new-password" class="form-control" placeholder="新しいパスワードを入力">
            </div>
            <div class="form-group">
                <label for="new-password-confirm">新しいパスワード（確認）</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="新しいパスワードを入力（確認）">
            </div>

            <h3 class="row justify-content-center p-4">ユーザ管理</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名前</th>
                        <th>作成日</th>
                        <th>更新日</th>
                        <th>削除日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>{{ $user->updated_at->format('Y/m/d') }}</td>
                        <td>{{ $user->deleted_at ? $user->deleted_at->format('Y/m/d') : '' }}</td>
                        <td><button class="btn btn-link text-danger">削除</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <!-- ログインしているのがユーザの場合 -->
            <h1 class="row justify-content-center p-5">プロフィール</h1>
            <form action="{{ route('profile.update', Auth::id()) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group d-flex align-items-center">
                    <label for="username" class="col-form-label col-sm-3">ユーザ名：</label>
                    <input type="text" id="username" class="form-control" value="{{ $user->name }}" placeholder="編集ユーザ名" required />
                </div>
                <label for="username" class="col-form-label col-sm-10">メールアドレス：{{ $user->email }}</label>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-primary">更新</button>
                    </div>
                </div>
            </form>
            <a href="{{ route('password.request') }}" class="col-form-label col-sm-10">パスワード変更</a>
            @endif
        </div>
    </div>
</div>
@endsection