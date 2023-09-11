<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VIDEOSALON</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
@switch(request()->user()->authority)
    @case(1)
    @case(2)
    <a class="navbar-brand" href="{{ route('home') }}">VIDEOSALON @yield('nav_name')</a>
        @break
    @case(3)
    <a class="navbar-brand" href="{{ route('appraisers') }}">VIDEOSALON @yield('nav_name')</a>
        @break
    @case(4)
    <a class="navbar-brand" href="{{ route('adcodes') }}">VIDEOSALON @yield('nav_name')</a>
        @break
    @default
    <a class="navbar-brand" href="{{ route('home') }}">VIDEOSALON @yield('nav_name')</a>
        @break
@endswitch
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

        </ul>
        <ul class="navbar-nav ">
            <a class="nav-link active" href="#">{{ request()->user()->email }}</a>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                @can('fnc1')
                    <a class="dropdown-item" href="{{ route('home') }}">スーパートップ</a>
                @endcan
                @can('fnc2')
                    <a class="dropdown-item" href="{{ route('users') }}">利用者管理</a>
                @endcan
                @can('fnc3')
                    <a class="dropdown-item" href="{{ route('appraisers') }}">占い師管理</a>
                @endcan
                @can('fnc4')
                    <a class="dropdown-item" href="{{ route('messages') }}">メッセージ管理</a>
                @endcan
                @can('fnc5')
                    <a class="dropdown-item" href="{{ route('blogs') }}">blog管理</a>
                @endcan
                @can('fnc6')
                    <a class="dropdown-item" href="{{ route('adcodes') }}">広告管理</a>
                @endcan
                @can('fnc7')
                    <a class="dropdown-item" href="{{ route('businesses') }}">事業管理</a>
                @endcan
                @can('fnc8')
                    <a class="dropdown-item" href="{{ route('reviews') }}">レビュー管理</a>
                @endcan
                @can('fnc9')
                    <a class="dropdown-item" href="{{ route('numerics') }}">数値管理</a>
                @endcan


                @can('fnc10')
                    <a class="dropdown-item" href="{{ route('rankings') }}">ランキング管理</a>
                @endcan

                    <a class="dropdown-item" href="{{ route('logout') }}">ログアウト</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
@yield('script')
@yield('local-script')
</body>

</html>
