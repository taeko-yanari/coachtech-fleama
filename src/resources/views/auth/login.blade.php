@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="container">
  <h1 class="login__title">ログイン</h1>
  <form action="/login" method="post" class="login__form">
    @csrf
    <div class="login__group">
      <label for="email" class="login__label">メールアドレス</label>
      <input type="text" name="email" id="email" class="login__input" value="{{ old('email') }}">

      @if ($errors->get('email'))
      <ul class="error-messages">
        @foreach ($errors->get('email') as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <div class="login__group">
      <label for="password" class="login__label">パスワード</label>
      <input type="password" name="password" id="password" class="login__input">

      @if ($errors->get('password'))
      <ul class="error-messages">
        @foreach ($errors->get('password') as $message)
        <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <button type="submit" class="login__submit">ログインする</button>
  </form>
  <div class="login__register-link-wrapper">
    <a href="{{ route('register') }}" class="login__register-link">会員登録はこちら</a>
  </div>
</div>
@endsection