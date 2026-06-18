@extends('layouts/app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/edit_address.css') }}">
@endsection

@section('content')
  <div class="container">
    <h1 class="edit-address__title">住所の変更</h1>
    <form action="{{ route('purchase.address.update', $item->id) }}" method="post" class="edit-address__form">
      @csrf
      <div class="edit-address__group">
        <label for="shipping_postal_code" class="edit-address__label">郵便番号</label>
        <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="edit-address__input" value="{{ old('shipping_postal_code') }}">

        @if ($errors->get('shipping_postal_code'))
          <ul class="error-messages">
            @foreach ($errors->get('shipping_postal_code') as $message)
              <li>{{ $message }}</li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="edit-address__group">
        <label for="shipping_address" class="edit-address__label">住所</label>
        <input type="text" name="shipping_address" id="shipping_address" class="edit-address__input" value="{{ old('shipping_address') }}" >

        @if ($errors->get('shipping_address'))
          <ul class="error-messages">
            @foreach ($errors->get('shipping_address') as $message)
              <li>{{ $message }}</li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="edit-address__group">
        <label for="shipping_building" class="edit-address__label">建物名</label>
        <input type="text" name="shipping_building" id="shipping_building" class="edit-address__input" value="{{ old('shipping_building') }}">

        @if ($errors->get('shipping_building'))
          <ul class="error-messages">
            @foreach ($errors->get('shipping_building') as $message)
              <li>{{ $message }}</li>
            @endforeach
          </ul>
        @endif
      </div>

      <button type="submit" class="edit-address__submit">更新する</button>
    </form>
  </div>
@endsection