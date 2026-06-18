@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
<div class="container">
  <div class="purchase__flex">
    <div class="purchase__flex-left">
      <div class="purchase__item-info">
        <div class="item-info__image-group">
          <img src="{{ asset($item->images->first()->image_url) }}" alt="" class="item-info__image">
        </div>
        <div class="item-info__text">
          <h1 class="item-info__name">{{ $item->name }}</h1>
          <p class="item-info__price"><span class="item-info__en">¥</span>{{ number_format($item->price) }}</p>
        </div>
      </div>
      <form action="{{ route('purchase.store', $item->id) }}" method="post" class="purchase__form" id="purchase__form">
        @csrf
        <div class="purchase__payment-method">
          <h2 class="purchase__section-title">支払い方法</h2>
          
          <div class="payment-method__wrapper">
            <select name="payment_method" id="payment_method" class="payment-method__select">
              <option value="">選択してください</option>
              <option value="コンビニ支払い" {{ old('payment_method', session('payment_method')) === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
              <option value="カード支払い" {{ old('payment_method', session('payment_method')) === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
          </div>
          @if ($errors->has('payment_method'))
          <ul class="error-messages">
            @foreach ($errors->get('payment_method') as $message)
            <li>{{ $message }}</li>
            @endforeach
          </ul>
          @endif
        </div>
        <div class="purchase__shipping-address">
          <div class="shipping-address__info">
            <h2 class="purchase__section-title">配送先</h2>
            <div class="purchase__shipping-address__text">
              <p class="postal_code">{{ $shipping_postal_code }}</p>
              <input type="hidden" name="shipping_postal_code" value="{{ $shipping_postal_code }}">
              <p class="address">{{ $shipping_address }}  {{ $shipping_building }}</p>
              <input type="hidden" name="shipping_address" value="{{ $shipping_address }}">
              <input type="hidden" name="shipping_building" value="{{ $shipping_building }}">
            </div>

            @if ($errors->has('shipping_postal_code'))
            <ul class="error-messages">
              @foreach ($errors->get('shipping_postal_code') as $message)
              <li>{{ $message }}</li>
              @endforeach
            </ul>
            @endif
                
            @if ($errors->has('shipping_address'))
            <ul class="error-messages">
              @foreach ($errors->get('shipping_address') as $message)
              <li>{{ $message }}</li>
              @endforeach
            </ul>
            @endif
          </div>

          <div class="shipping-address__edit">
            <a href="{{ route('purchase.address.edit', [$item->id, 'payment_method' => session('payment_method')]) }}" class="shipping-address__edit-link">変更する</a>
          </div>
        </div>
      </form>
    </div>
        
    <div class="purchase__flex-right">
      <div class="purchase__info">
        <table class="purchase__table">
          <tr>
            <th class="table__heading">商品代金</th>
            <td class="table__text table__price"><span class="table__text-en">¥</span>{{ number_format($item->price) }}</td>
          </tr>
          <tr>
            <th class="table__heading">支払い方法</th>
            <td class="table__text table__payment-method"></td>
          </tr>
        </table>
            
        <div class="purchase__button">
          <button type="submit" class="purchase__button-submit" form="purchase__form">購入する</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/payment-method.js') }}"></script>
@endsection
