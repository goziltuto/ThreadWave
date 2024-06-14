@extends('layouts.layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-md-offset-3 col-md-6">
            <h1 class="row justify-content-center p-5">アカウント作成</h1>
            <nav class="card mt-5">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}" />
                        </div>
                        <div class="form-group">
                            <label for="name">ユーザ名</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="ユーザ名を入力" value="{{ old('name') }}" />
                        </div>
                        <div class="form-group">
                            <label for="password">パスワード</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="新しいパスワードを入力">
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">パスワード（確認）</label>
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="新しいパスワードを入力（確認）">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">送信</button>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</div>
@endsection