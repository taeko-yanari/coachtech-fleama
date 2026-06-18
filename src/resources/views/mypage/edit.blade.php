@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')
  <div class="container">
    <h1 class="profile__title">プロフィール設定</h1>
    <form action="{{ route('mypage.update') }}" method="post" class="profile__form" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="profile__image-field">
        <input type="file" name="profile_image_path" id="profile_image_path" class="profile__file-input">

        <div class="profile__layout">
          <div id="preview-area" class="profile__image-wrap">
            <img id="preview-image" class="profile__image" alt="" src="{{ session('temp_image_path') ? asset('storage/' . session('temp_image_path')) : $profile->profile_image_path }}">
          </div>
          <div class="profile__file">
            <label for="profile_image_path" class="profile__file-btn">画像を選択する</label>
          </div>
        </div>

        <input type="hidden" name="temp_image" id="temp_image" value="{{ old('temp_image') }}">

        @if ($errors->has('profile_image_path'))
        <ul class="error-messages">
          @foreach ($errors->get('profile_image_path') as $message)
          <li>{{ $message }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <div class="profile__group">
        <label for="name" class="profile__label">ユーザー名</label>
        <input type="text" name="name" id="name" class="profile__input" value="{{ old('name', $profile->name) }}">

        @if ($errors->get('name'))
        <ul class="error-messages">
          @foreach ($errors->get('name') as $message)
            <li>{{ $message }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <div class="profile__group">
        <label for="postal_code" class="profile__label">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code" class="profile__input" value="{{ old('postal_code', $profile->postal_code) }}">

        @if ($errors->get('postal_code'))
          <ul class="error-messages">
          @foreach ($errors->get('postal_code') as $message)
            <li>{{ $message }}</li>
          @endforeach
          </ul>
        @endif
      </div>

      <div class="profile__group">
        <label for="address" class="profile__label">住所</label>
        <input type="text" name="address" id="address" class="profile__input" value="{{ old('address', $profile->address) }}">

        @if ($errors->get('address'))
        <ul class="error-messages">
          @foreach ($errors->get('address') as $message)
          <li>{{ $message }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <div class="profile__group">
        <label for="building" class="profile__label">建物名</label>
        <input type="text" name="building" id="building" class="profile__input" value="{{ old('building', $profile->building) }}">

        @if ($errors->get('building'))
        <ul class="error-messages">
          @foreach ($errors->get('building') as $message)
          <li>{{ $message }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <button type="submit" class="profile__submit">更新する</button>
    </form>

  </div>
@endsection

@section('js')
<script src="{{ asset('js/image-preview-edit.js') }}"></script>
 @endsection