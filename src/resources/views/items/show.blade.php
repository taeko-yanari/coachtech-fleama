@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="detail__flex">
            <div class="detail__left">
                @foreach ($item->images as $image)
                    <img src="{{ $item->images->first()->image_url }}" alt="{{ $item->name }}" class="detail__image">
                @endforeach
            </div>
            <div class="detail__right">
                <h1 class="detail__item-name">{{ $item->name }}</h1>
                <p class="detail__brand-name">{{ $item->brand_name }}</p>
                <p class="detail__price"><span class="detail__price-yen">&yen;</span>{{ number_format($item->price) }}<span class="detail__price-tax">(税込)</span></p>

                <div class="detail__icon">
                    <div class="detail__likes">
                        @if ($item->likes()->where('user_id', Auth::id())->exists())
                        <form action="{{ route('likes.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="detail__icon-button">
                                <img src="{{ asset('images/heart-pink.png') }}" alt="いいねアイコン" class="detail__icon-heart">
                            </button>
                            <p class="detail__count">{{ $item->likes->count() }}</p>
                        </form>
                        @else
                        <form action="{{ route('likes.store', $item->id) }}" method="post">
                            @csrf
                            <button type="submit" class="detail__icon-button">
                                <img src="{{ asset('images/heart.png') }}" alt="いいねアイコン" class="detail__icon-heart">
                            </button>
                        </form>
                        <p class="detail__count">{{ $item->likes->count() }}</p>
                        @endif
                    </div>
                    <div class="detail__comments">
                        <img src="{{ asset('images/speech-bubble.png') }}" alt="コメントアイコン" class="detail__icon-speech">
                        <p class="detail__count">{{ $item->comments->count() }}</p>
                    </div>
                </div>

                @if ($item->status === 'sold')
                    <p class="detail__order-button detail__order-button--disabled">購入手続きへ</p>
                @else
                    <a href="{{ route('purchase.create', $item->id) }}" class="detail__order-button">購入手続きへ</a>
                @endif

                <div class="detail__description-section">
                    <div class="detail__description">
                        <h2 class="detail__title">商品説明</h2>
                        <div class="detail__description-box">
                            <p class="detail__description-text">{{$item->description}}</p>
                        </div>
                    </div>
                    
                    <div class="detail__info">
                        <h2 class="detail__title">商品の情報</h2>
                        <div class="detail__category">
                            <h3 class="detail__sub-title">カテゴリー</h3>
                            @foreach ($item->categories as $category)
                                <p class="category__item">{{ $category->name }}</p>
                            @endforeach
                        </div>
                        <div class="detail__condition">
                            <h3 class="detail__sub-title">商品の状態</h3>
                            <p class="condition__text">{{ $item->condition }}</p>
                        </div>
                    </div>
                    <div class="detail__comment">
                        <h3 class="detail__comment-title">コメント<span class="detail__comment-count">({{ $item->comments->count() }})</span></h3>
                        @foreach ($item->comments as $comment)
                            <div class="comment__item">
                                <div class="comment__user">
                                    <img src="{{ $comment->user->profile_image_path }}" alt="" class="comment__user-image">

                                    <p class="comment__user-name">{{ $comment->user->name }}</p>
                                </div>
                                <p class="comment__text">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="detail__add-comment">
                        <h3 class="add-comment__title">商品へのコメント</h3>
                        <form action="{{ route('comments.store', $item->id) }}" method="post" class="add-comment__form">
                            @csrf
                            <textarea name="comment" cols="70" rows="15" class="add-comment__area">{{ old('comment') }}</textarea>
                            @if ($errors->get('comment'))
                                <ul class="error-messages">
                                    @foreach ($errors->get('comment') as $message)
                                    <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            <input type="submit" value="コメントを送信する" class="add-comment__submit">
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection