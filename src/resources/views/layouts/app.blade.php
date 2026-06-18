<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>COACHTECH-fleamarket</title>

  {{-- Googleフォント --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  {{-- cssの読み込み --}}
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">

  @yield('css')
</head>

<body>
  @if (in_array(Route::currentRouteName(), ['register','login','verification.notice']))
    <header class="header">
      <div class="header__inner">
        <div class="header__site-logo">
          <a href="{{ route('items.index') }}">
            <img src="{{ asset('images/header-logo.png') }}" alt="COACHTECH" class="header__logo-image">
          </a>
        </div>
      </div>
    </header>
  @else
    <header class="header">
      <div class="header__inner">
        <div class="header-left">
          <div class="header__site-logo">
            <a href="{{ route('items.index') }}">
              <img src="{{ asset('images/header-logo.png') }}" alt="COACHTECH" class="header__logo-image">
            </a>
          </div>
        </div>

        <div class="header-center">
          
            @if(Route::currentRouteName() === 'mypage.index' || Route::currentRouteName() === 'mypage.search')
            {{-- mypage.searchに向ける --}}
            <form action="{{ route('mypage.search') }}" method="get" class="header__search-form">
              <input type="text" name="keyword" id="keyword" class="header__search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'sell' }}">
          </form>
            @else
            {{-- items.searchに向ける --}}
            <form action="{{ route('items.search') }}" method="get" class="header__search-form">
              <input type="text" name="keyword" id="keyword" class="header__search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'recommend' }}">
          </form>
            @endif
            
        </div>

        @if (Auth::check())
          <div class="header-right">
            <nav>
              <ul class="header__nav-list">
                <li>
                  <form action="{{ url('/logout') }}" method="post">
                    @csrf
                    <button type="submit" class="header__logout-button">ログアウト</button>
                  </form>
                </li>
                <li><a href="{{ route('mypage.index') }}">マイページ</a></li>
                <li><a href="{{ route('items.create') }}" class="header__add-button">出品</a></li>
              </ul>
            </nav>
          </div>
        @else
          <div class="header-right">
            <nav>
              <ul class="header__nav-list">
                <li><a href="/login">ログイン</a></li>
                <li><a href="{{ route('mypage.index') }}">マイページ</a></li>
                <li><a href="{{ route('items.create') }}" class="header__add-button">出品</a></li>
              </ul>
            </nav>
          </div>
        @endif
      </div>
    </header>
  @endif

  <main>
    @yield('content')
    @yield('js')
  </main>
</body>
</html>