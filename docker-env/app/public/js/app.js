document.getElementById('logout').addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('logout-form').submit();
});


$(document).ready(function () {
    $('.edit-comment').click(function (e) {
        e.preventDefault();
        var commentId = $(this).data('id');
        $('#edit-comment-form-' + commentId).show(); // フォームを表示
        $('#display-comment-' + commentId).hide(); // コメントを非表示
    });

    $('.update-comment').click(function () {
        var commentId = $(this).data('id');
        var updatedComment = $('#edit-comment-form-' + commentId + ' textarea[name="edited-comment"]').val();
        console.log('Ajax処理を開始します:', commentId, updatedComment);
        $.ajax({
            url: '/comments/' + commentId,
            type: 'PATCH',
            data: {
                comment: updatedComment,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Success:', response);
                $('#display-comment-' + commentId + ' .comment-text').text(updatedComment);
                $('#edit-comment-form-' + commentId).hide();
                $('#display-comment-' + commentId).show();

                // 更新されたコメントに対してリンク化を適用
                isValidUrl();
            },
            error: function (xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });

    $('.cancel-edit').click(function () {
        var commentId = $(this).data('id');
        $('#edit-comment-form-' + commentId).hide(); // フォームを非表示
        $('#display-comment-' + commentId).show(); // コメントを表示
    });

    /*以下削除処理*/
    // 削除ボタンがクリックされたときの処理
    $('.delete-comment').click(function (e) {
        e.preventDefault(); // デフォルトのイベントをキャンセル
        var commentId = $(this).data('id'); // 削除対象のコメントIDを取得

        if (confirm('本当にこのコメントを削除しますか？')) {
            // 削除確認後、非同期でコメントを削除
            $.ajax({
                url: '/comments/' + commentId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#display-comment-' + commentId + ' .comment-text').text('このコメントは削除されました').addClass('text-secondary');
                    $('#display-comment-' + commentId + ' .edit-comment').hide();
                    $('#display-comment-' + commentId + ' .delete-comment').hide();
                    $('#display-comment-' + commentId + ' .restore-comment').show(); // 復元ボタンを表示
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // エラーが発生した場合はコンソールにログを出力
                }
            });
        }
        // コメントの復元ボタンがクリックされたときの処理
        $(document).on('click', '.restore-comment', function (e) {
            e.preventDefault();
            var commentId = $(this).data('id');

            if (confirm('コメントを復元しますか？')) {
                // コメントの復元を行う非同期処理
                $.ajax({
                    url: '/comments/' + commentId + '/restore',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#display-comment-' + commentId + ' .comment-text').text('このコメントは復元されました').addClass('text-warning');
                        $('#display-comment-' + commentId + ' .edit-comment').show();
                        $('#display-comment-' + commentId + ' .delete-comment').show();
                        $('#display-comment-' + commentId + ' .restore-comment').hide(); // 復元ボタンを非表示
                        alert("コメントが復元されました。ページを再読み込みします");
                        location.reload(); // ページをリロード
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });


    /*/ダークモード切り替え処理/*/
    const body = document.querySelector('body');
    const darkmodeBtn = document.getElementById('darkmodeBtn');

    // 現在の画面モードを読み込み
    var mode = localStorage.getItem('mode');
    if (mode === 'dark') {
        body.classList.add('dark');
        darkmodeBtn.checked = true;
    }

    darkmodeBtn.addEventListener('click', () => {
        body.classList.toggle('dark');
        var dropdownMenu = document.querySelector('.dropdown-menu');
        if (mode === 'normal') {
            body.classList.add('dark');
            dropdownMenu.classList.add('dropdown-menu-dark'); // ハンバーガーメニューを黒にする
            localStorage.setItem('mode', 'dark');
            mode = 'dark';
            console.log('ダークモード');
        } else {
            body.classList.remove('dark');
            dropdownMenu.classList.remove('dropdown-menu-dark');// ハンバーガーメニューを白にする
            localStorage.setItem('mode', 'normal');
            mode = 'normal';
            console.log('ホワイトモード');
        }
    });



    // 投稿を検索して表示する処理
    $('#search-form').submit(function (e) {
        e.preventDefault(); // デフォルトのフォーム送信をキャンセル
        var searchText = $('#search-text').val(); // 検索テキストを取得
        console.log('検索キーワード:', searchText);

        // ボタンを無効化し、グレーアウトさせる
        $('#search-btn').prop('disabled', true);

        // 検索ボタンのテキストを非表示にし、スピナーを表示する
        $('#search-btn-text').addClass('visually-hidden');
        $('#search-spinner').removeClass('d-none');

        // Ajaxリクエストを送信して検索を実行
        $.ajax({
            url: '/search',
            type: 'GET',
            data: {
                search_text: searchText
            },
            success: function (response) {
                console.log('検索結果:', response);
                // 検索結果を表示するための関数を呼び出す
                displaySearchResults(response);

                // スピナーを非表示にし、検索ボタンのテキストを表示する
                $('#search-btn-text').removeClass('visually-hidden');
                $('#search-spinner').addClass('d-none');

                // ボタンを有効化し、元の色に戻す
                $('#search-btn').prop('disabled', false);
            },
            error: function (xhr) {
                console.error('検索エラー:', xhr.responseText);
            }
        });
    });

    // 検索結果を表示する関数
    function displaySearchResults(results) {
        var searchResultsDiv = $('#search-results');
        searchResultsDiv.empty(); // 検索結果をクリア

        if (results.length > 0) {
            var resultList = $('<ul class="list-group"></ul>'); // 検索結果を表示するリストを作成
            // 検索結果をリストアイテムとして追加
            $.each(results, function (index, post) {
                var listItem = $('<li class="list-group-item text-left align-items-start p-1"></li>');
                var postLink = $('<a href="/post/' + post.id + '" class="btn btn-link"><strong>' + post.title + '</strong></a>');
                listItem.append(postLink);
                resultList.append(listItem);
            });
            searchResultsDiv.append(resultList); // リストを検索結果表示エリアに追加
        } else {
            searchResultsDiv.append('<p>該当する投稿が見つかりませんでした。</p>'); // 該当する投稿がない場合のメッセージを表示
        }
    }

    // カテゴリ並び替え処理
    $(document).ready(function () {
        $('#category-submit-btn').click(function (e) {
            e.preventDefault();
            var categoryId = $('#selected-category').val();

            // ボタンを無効化し、グレーアウトさせる
            $('#search-btn').prop('disabled', true);

            // ローディングアニメーションを表示
            $('#category-btn-text').addClass('d-none');
            $('#category-spinner').removeClass('d-none');

            $.ajax({
                url: '/posts/category/' + categoryId,
                type: 'GET',
                success: function (data) {
                    if (data.length > 0) {
                        var postsHtml = '';
                        data.forEach(function (post) {
                            postsHtml += '<li class="list-group-item text-left align-items-start p-1">' +
                                '<a href="/post/' + post.id + '" class="btn btn-link"><strong>' + post.title + '</strong></a>' +
                                '</li>';
                        });
                        $('#category-posts').html('<ul class="list-group">' + postsHtml + '</ul>');
                    } else {
                        $('#category-posts').html('<p>該当する投稿が見つかりませんでした。</p>');
                    }

                    // ローディングアニメーションを非表示
                    $('#category-btn-text').removeClass('d-none');
                    $('#category-spinner').addClass('d-none');
                },
                error: function (error) {
                    console.log(error);

                    // エラーハンドリング後、ローディングアニメーションを非表示
                    $('#category-btn-text').removeClass('d-none');
                    $('#category-spinner').addClass('d-none');
                }
            });
        });
    });

    /* 以下コメント処理 */
    // 投稿フォームのsubmitイベントを監視
    $('#post-form').submit(function (event) {
        // ボタンを無効化し、グレーアウトさせる
        $('#post-submit-btn').prop('disabled', true);

        // ボタンのテキストを非表示にし、スピナーを表示する
        $('#post-btn-text').addClass('d-none');
        $('#post-spinner').removeClass('d-none');

        // フォーム送信を防止しないようにする
        return true;
    });

    // コメント送信後ボタンをグレーアウト
    $(document).ready(function () {
        // フォームのsubmitイベントを監視
        $('#comment-form').submit(function (event) {
            // ボタンを無効化し、グレーアウトさせる
            $('#comment-submit-btn').prop('disabled', true);

            // ボタンのテキストを非表示にし、スピナーを表示する
            $('#comment-btn-text').addClass('d-none');
            $('#comment-spinner').removeClass('d-none');

            // フォームが送信されるのを許可する
            return true;
        });


    });

});

// コメントにURLが含まれていたらリンク化する
// URLが有効かどうかを確認する関数
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

// コメントを含む要素を取得
const comments = document.querySelectorAll('.comment-text');

comments.forEach(comment => {
    // コメントテキストを取得
    let content = comment.innerHTML;
    // 改行ごとに分割
    let parts = content.split(/\n/);

    // 新しいコンテンツを保持するための配列
    let newContent = parts.map(part => {
        let trimmedPart = part.trim();
        if (isValidUrl(trimmedPart)) {
            // URL部分をリンク化
            return `<a href="${trimmedPart}" target="_blank">${trimmedPart}</a>`;
        } else {
            // URLでない部分はそのまま
            return part;
        }
    }).join('\n');

    // 新しいコンテンツを設定
    comment.innerHTML = newContent;
});



/* タイピングアニメーション */
var TxtRotate = function (el, toRotate, period) {
    this.toRotate = toRotate;
    this.el = el;
    this.loopNum = 0;
    this.period = parseInt(period, 10) || 2000;
    this.txt = '';
    this.tick();
    this.isDeleting = false;
};

TxtRotate.prototype.tick = function () {
    var i = this.loopNum % this.toRotate.length;
    var fullTxt = this.toRotate[i];

    if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
    } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
    }

    this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

    var that = this;
    var delta = 300 - Math.random() * 100;

    if (this.isDeleting) { delta /= 2; }

    if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
    } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
    }

    setTimeout(function () {
        that.tick();
    }, delta);
};

window.onload = function () {
    var elements = document.getElementsByClassName('txt-rotate');
    for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-rotate');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
            new TxtRotate(elements[i], JSON.parse(toRotate), period);
        }
    }
    // INJECT CSS
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = ".txt-rotate > .wrap { border-right: 0.08em solid #666 }";
    document.body.appendChild(css);
};




/* 時計 */
const clock = () => {
    // 現在の日時・時刻の情報を取得
    const d = new Date();

    // 年を取得
    let year = d.getFullYear();
    // 月を取得
    let month = d.getMonth() + 1;
    // 日を取得
    let date = d.getDate();
    // 曜日を取得
    let dayNum = d.getDay();
    const weekday = ["日", "月", "火", "水", "木", "金", "土"];
    let day = weekday[dayNum];
    // 時を取得
    let hour = d.getHours();
    // 分を取得
    let min = d.getMinutes();
    // 秒を取得
    let sec = d.getSeconds();

    // 1桁の場合は0を足して2桁に
    month = month < 10 ? "0" + month : month;
    date = date < 10 ? "0" + date : date;
    hour = hour < 10 ? "0" + hour : hour;
    min = min < 10 ? "0" + min : min;
    sec = sec < 10 ? "0" + sec : sec;

    // 日付・時刻の文字列を作成
    let today = `${year}.${month}.${date} ${day}`;
    let time = `${hour}:${min}:${sec}`;

    // 文字列を出力
    document.querySelector(".clock-date").innerText = today;
    document.querySelector(".clock-time").innerText = time;
};

// 1秒ごとにclock関数を呼び出す
setInterval(clock, 1000);

// ハンバーガーメニュー内のクリック処理
document.querySelectorAll('.dropdown-menu').forEach(function (element) {
    element.addEventListener('click', function (event) {
        event.stopPropagation();
    });
});

// スレッド投稿時と表示のバリデーション
$(document).ready(function () {
    $('#post-submit-btn').prop('disabled', true);

    // タイトルまたはカテゴリが変更されたとき、投稿ボタンを有効にする
    $('#title, #category').on('input', function () {
        var title = $('#title').val().trim();
        var category = $('#category').val();
        if (title !== '' && category !== null) {
            $('#post-submit-btn').prop('disabled', false);
        } else {
            $('#post-submit-btn').prop('disabled', true);
        }
    });


    // 以下カテゴリ並び替え処理
    $('#category-submit-btn').prop('disabled', true);

    // カテゴリが選択されたときの処理
    $('#selected-category').change(function () {
        var selectedCategory = $(this).val();
        if (selectedCategory !== '') {
            $('#category-submit-btn').prop('disabled', false);
        } else {
            $('#category-submit-btn').prop('disabled', true);
        }
    });
});