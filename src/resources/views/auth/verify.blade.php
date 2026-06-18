@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
<div class="container">
  <div class="verify__group">
    <p class="verify__text">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
    
    <a href="http://localhost:8025" class="verify__submit-button">
      認証はこちら
    </a>
  </div>
  <div class="verify__resent-group">
    <form action="/email/verification-notification" method="post">
      @csrf
      <button type="submit" class="verify__resend-link">認証メールを再送する</button>
    </form>
  </div>
</div>
@endsection