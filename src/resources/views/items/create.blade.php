@extends('layouts/app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
  <div class="container">
    <h1 class="sell__title">商品の出品</h1>
      
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form action="{{ route('items.store') }}" method="post" class="sell__form" enctype="multipart/form-data">
      @csrf
      <div class="sell__group">
        <label for="image" class="sell__label">商品画像</label>
        
        <div class="sell__image-upload-area" id="uploadPlaceholder">
          <button type="button" class="sell__image-button" onclick="document.getElementById('imageInput').click()">
            画像を選択する
          </button>
        </div>

        <div class="sell__preview-container" id="previewContainer">
          <img src="" alt="" id="previewImage">
          <button type="button" class="sell__remove-image-btn" id="removeImage">x</button>
        </div>

        @if(session('temp_image_path'))
        <input type="hidden" id="savedImageUrl" value="{{ asset('storage/' . session('temp_image_path')) }}">
        @endif
        
        <input type="file" name="image" id="imageInput" style="display: none">
        @if ($errors->has('image'))
        <ul class="error-messages">
          @foreach ($errors->get('image') as $message)
          <li>{{ $message }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <div class="sell__section">
        <h2 class="sell__section-title">商品の詳細</h2>
          
        <div class="sell__group">
          <label for="categories" class="sell__label">カテゴリー</label>
          <div class="sell__checkbox-group">
            @foreach ($categories as $category)
            <input type="checkbox" name="category_ids[]" id="category_{{ $category->id }}" value="{{ $category->id }}" class="sell-checkbox" {{ (in_array($category->id, old('category_ids', []))) ? 'checked' : '' }}>
                
            <label for="category_{{ $category->id }}" class="sell__checkbox-label">{{ $category->name }}</label>
            @endforeach
          </div>
          @if ($errors->has('category_ids'))
          <ul class="error-messages">
            @foreach ($errors->get('category_ids') as $message)
            <li>{{ $message }}</li>
            @endforeach
          </ul>
          @endif
        </div>

        <div class="sell__group">
          <label for="condition" class="sell__label">商品の状態</label>
          <div class="condition__wrapper">
            <select name="condition" id="condition" class="condition__select">
              <option value="">選択してください</option>
              <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }} >良好</option>
              <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }} >目立った傷や汚れなし</option>
              <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }} >やや傷や汚れあり</option>
              <option value="状態が悪い"  {{ old('condition') === '状態が悪い' ? 'selected' : '' }} >状態が悪い</option>
            </select>
          </div>
          
          @if ($errors->has('condition'))
          <ul class="error-messages">
            @foreach ($errors->get('condition') as $message)
            <li>{{ $message }}</li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>

      <div class="sell__section">
        <h2 class="sell__section-title">商品名と説明</h2>
        <div class="sell__group">
          <label for="name" class="sell__label">商品名</label>
          <input type="text" name="name" id="name" class="sell__input" value="{{ old('name') }}">
          @if ($errors->has('name'))
            <ul class="error-messages">
              @foreach ($errors->get('name') as $message)
              <li>{{ $message }}</li>
              @endforeach
            </ul>
          @endif
        </div>

        <div class="sell__group">
          <label for="brand" class="sell__label">ブランド名</label>
          <input type="text" name="brand_name" id="brand_name" class="sell__input" value="{{ old('brand_name') }}">
          @if ($errors->has('brand_name'))
            <ul class="error-messages">
            @foreach ($errors->get('brand_name') as $message)
              <li>{{ $message }}</li>
            @endforeach
            </ul>
          @endif
        </div>

        <div class="sell__group">
          <label for="description" class="sell__label">商品の説明</label>
          <textarea name="description" id="description" cols="30" rows="10" class="sell__texrarea">{{ old('description') }}</textarea>
          @if ($errors->has('description'))
          <ul class="error-messages">
            @foreach ($errors->get('description') as $message)
            <li>{{ $message }}</li>
            @endforeach
          </ul>
          @endif
        </div>

        <div class="sell__group">
          <label for="description" class="sell__label">販売価格</label>
          <div class="price-input-wrapper">
            <input type="text" name="price" id="price" value="{{ old('price') }}" class="sell__input sell__price-input" placeholder="¥">
          </div>
          @if ($errors->has('price'))
          <ul class="error-messages">
            @foreach ($errors->get('price') as $message)
            <li>{{ $message }}</li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
      <button type="submit" class="sell__submit">出品する</button>
    </form>
  </div>
@endsection

@section('js')
<script src="{{ asset('js/image-upload.js') }}"></script>
@endsection