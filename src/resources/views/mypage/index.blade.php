@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="profile__section">
  <div class="profile__layout">
    <div class="profile__info">
      <img src="{{ $profile->profile_image_path }}" alt="" class="profile__image">
      <p class="profile__name">{{ $profile->name }}</p>
    </div>
    <div class="profile__edit">
      <a href="{{ route('mypage.edit') }}" class="profile__edit-button">プロフィールを編集</a>
    </div>
  </div>
</div>
<div class="tabs">
  <div class="tabs__tab-list">
    <a href="{{ route('mypage.search', ['tab' => 'sell', 'keyword' => request('keyword')]) }}" class="tab {{ $activeTab === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage.search', ['tab' => 'purchase', 'keyword' => request('keyword')]) }}" class="tab {{ $activeTab === 'purchase' ? 'active' : '' }}">購入した商品</a>
  </div>
</div>
    
<div class="tabs__panel {{ $activeTab === 'sell' ? 'active' : '' }}">
  <div class="tabs__item-list">
    @foreach ($sellItems as $item)
    <a href="{{ route('items.show', $item->id) }}" class="tabs__item-card">
      <img src="{{ $item->images->first()->image_url }}" alt="{{ $item->name }}" class="tabs__item-img">
      <div class="tabs__item-info">
        <p class="tabs__item-name">{{ $item->name }}</p>
        <p class="tabs__item-status" style="display:none">sold</p>
      </div>
    </a>
    @endforeach
  </div>
</div>

<div class="tabs__panel {{ $activeTab === 'purchase' ? 'active' : '' }}">
  @auth
  <div class="tabs__item-list">
    @foreach ($purchaseItems as $item)
    <a href="{{ route('items.show', $item->id) }}" class="tabs__item-card">
      <img src="{{ $item->images->first()->image_url }}" alt="{{ $item->name }}" class="tabs__item-img">
      <div class="tabs__item-info">
        <p class="tabs__item-name">{{ $item->name }}</p>
        <p class="tabs__item-status" {!! $item->status === 'sold' ? 'style="display:block"' : 'style="display:none"' !!}>sold</p>
      </div>
    </a>
    @endforeach
  </div>
  @endauth
</div>
@endsection