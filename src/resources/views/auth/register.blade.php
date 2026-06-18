@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="container">
  <h1 class="register__title">会員登録</h1>
  <form action="/register" method="post" class="register__form">
    @csrf
    <div class="register__group">
      <label for="name" class="register__label">ユーザー名</label>
      <input type="text" name="name" id="name" class="register__input" value="{{ old('name') }}">
      
      @if ($errors->get('name'))
      <ul class="error-messages">
        @foreach ($errors->get('name') as $message)
        <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <div class="register__group">
      <label for="email" class="register__label">メールアドレス</label>
      <input type="text" name="email" id="email" class="register__input" value="{{ old('email') }}">

      @if ($errors->get('email'))
      <ul class="error-messages">
        @foreach ($errors->get('email') as $message)
        <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <div class="register__group">
      <label for="password" class="register__label">パスワード</label>
      <input type="password" name="password" id="password" class="register__input">
      
      @if ($errors->get('password'))
      <ul class="error-messages">
        @foreach ($errors->get('password') as $message)
        <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <div class="register__group">
      <label for="password_confirmation" class="register__label">確認用パスワード</label>
      <input type="password" name="password_confirmation" id="password_confirmation" class="register__input">

      @if ($errors->get('password_confirmation'))
      <ul class="error-messages">
        @foreach ($errors->get('password_confirmation') as $message)
        <li>{{ $message }}</li>
        @endforeach
      </ul>
      @endif
    </div>

    <button type="submit" class="register__submit">登録する</button>
  </form>
  <div class="register__login-link-wrapper">
    <a href="{{ route('login') }}" class="register__login-link">ログインはこちら</a>
  </div>
</div>
@endsection