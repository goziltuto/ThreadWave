<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2ch風掲示板</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">2ch風 掲示板(ThreadWave)</h1>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            投稿フォーム
                        </div>
                        <div class="card-body">
                            <form class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="name" class="sr-only">投稿タイトル</label>
                                    <input type="text" class="form-control col-md-10" id="name" placeholder="投稿タイトルを入力">
                                </div>
                                <button type="submit" class="btn btn-primary">投稿する</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row mt-3">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        投稿一覧
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($posts as $post)
                            <li class="list-group-item">名前: {{ $post['name'] }}<br>メッセージ: {{ $post['title'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
