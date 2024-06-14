@extends('layouts.layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-md-offset-3 col-md-6">
            <div class=" justify-content-center d-flex">
                <img src="{{ asset('img/logo.jpg') }}" class="logo" alt="ThreadWave">
            </div>
            <nav class="card mt-5">

                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}" />
                        </div>
                        <div class="form-group">
                            <label for="password">パスワード</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="パスワードを入力" />
                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary">ログイン</button>
                            <a href="{{ route('password.request') }}" class="pl-3">※パスワード忘れた方はこちら</a>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</div>
@endsection