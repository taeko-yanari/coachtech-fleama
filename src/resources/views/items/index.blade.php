@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
  <div class="tabs">
    <div class="tabs__tab-list">
      <a href="{{ route('items.search', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}" class="tab {{ $activeTab === 'recommend' ? 'active' : '' }}">おすすめ</a>
      <a href="{{ route('items.search', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="tab {{ $activeTab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
  </div>
    
  <div class="tabs__panel {{ $activeTab === 'recommend' ? 'active' : '' }}">
    <div class="tabs__item-list">
      @foreach ($recommendItems as $item)
        <a href="{{ route('items.show', $item->id) }}" class="tabs__item-card">
          <img src="{{ $item->images->first()->image_url }}" alt="{{ $item->name }}" class="tabs__item-img">
          <div class="tabs__item-info">
            <p class="tabs__item-name">{{ $item->name }}</p>
            <p class="tabs__item-status" {!! $item->status === 'sold' ? 'style="display:block"' : 'style="display:none"' !!}>sold</p>
          </div>  
        </a>
      @endforeach
    </div>
  </div>

  <div class="tabs__panel {{ $activeTab === 'mylist' ? 'active' : '' }}">
    @auth
    <div class="tabs__item-list">
      @foreach ($mylistItems as $item)
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