@extends('layouts.layout')
@section('content')
<div class="container">
    <div class=" justify-content-center d-flex">
        <img src="{{ asset('img/logo.jpg') }}" class="logo" alt="ThreadWave">
    </div>
    <h1 class="text-center">ThreadWave</h1>
    <div class="container">
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    投稿フォーム
                </div>
                <div class="card-body">
                    <form method="POST" action="/post" id="post-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" id="title" name="title" placeholder="投稿タイトルを入力" required />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="post-submit-btn">
                                    <span id="post-btn-text">投稿</span>
                                    <div class="spinner-border text-white d-none" role="status" id="post-spinner">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header justify-content-center d-flex">
                    投稿一覧
                </div>
                <!-- Tabs or Pills can be used in a card with the help of .nav-{tabs|pills} and .card-header-{tabs|pills} classes -->
                <div class="card-body">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">最新</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">人気</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="search-tab" data-bs-toggle="tab" href="#search" role="tab" aria-controls="search" aria-selected="false">検索</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <ul class="list-group">
                                @foreach($posts as $post)
                                <li class="list-group-item text-left align-items-start p-1">
                                    <a href="{{ route('post.show', $post->id) }}" class="btn btn-link">{{ $post->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <ul class="list-group">
                                @foreach($mostCommentedPosts as $post)
                                <li class="list-group-item text-left align-items-start p-1">
                                    <a href="{{ route('post.show', $post->id) }}" class="btn btn-link">{{ $post->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="search" role="tabpanel" aria-labelledby="search-tab">
                            <form id="search-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-text" name="search_text" placeholder="検索キーワードを入力" required />
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" id="search-btn">
                                            <span id="search-btn-text">検索</span>
                                            <div class="spinner-border text-white d-none" role="status" id="search-spinner">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- 検索結果表示エリア -->
                            <div id="search-results" class="p-3">
                                <!-- ここに検索結果を表示 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</body>

</html>