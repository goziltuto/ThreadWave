<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ThreadWave') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('stylesheet')

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="image-container">
                        <img src="{{ asset('img/logo.jpg') }}" class="header-logo" alt="ThreadWave">
                    </div>
                </a>
                <div class="clock">
                    <p class="clock-date"></p>
                    <p class="clock-time"></p>
                </div>

                <div class="my-navbar-control">
                    @if(Auth::check())
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="my-navbar-control">
                                    <a href="{{ route('profile.show') }}" class="dropdown-item my-navbar-item">{{ Auth::user()->name }}</a>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-header">
                                    <label class="toggle-button">
                                        <input type="checkbox" id="darkmodeBtn" />
                                    </label>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    お問い合わせ
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="#" id="logout" class="dropdown-item mt-navbar-item">ログアウト</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a class="my-navbar-item" href="{{ route('login') }}">ログイン</a>
                    /
                    <a class="my-navbar-item" href="{{ route('register') }}">新規作成</a>
                    @endif
                </div>

            </div>
        </nav>
        @yield('content')
    </div>
</body>

</html>