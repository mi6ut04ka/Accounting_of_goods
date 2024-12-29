@extends('layouts.products')

@section('title', $product->name)

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                @if($product->photos->isNotEmpty())
                    <img src="{{ asset("storage/{$product->photos->first()->url}") }}" alt="{{ $product->name }}" class="img-fluid rounded">
                @else
                    <img src="{{ asset('images/img.png') }}" alt="Default photo" class="img-fluid rounded">
                @endif
            </div>
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="text-muted">Цена: <strong>{{ number_format($product->price, 2) }} ₽</strong></p>
                <p>В наличии: <strong>{{ $product->in_stock }} шт.</strong></p>
                <button
                    class="btn btn-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#sold-modal-{{ $product->id }}">
                    Продано
                </button>
                <x-modalSold
                    id="sold-modal-{{ $product->id }}"
                    :id_product="$product->id">
                </x-modalSold>

                <!-- Кнопка для изменения -->
                <button class="btn btn-success">
                    @if($product->candle && $product->candle->containerCandle)
                        <a href="{{ route('products.container_candles.edit', $product->candle->containerCandle->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @elseif($product->candle && $product->candle->moldedCandle)
                        <a href="{{ route('products.molded_candles.edit', $product->candle->moldedCandle->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @elseif($product->gypsumProduct && $product->gypsumProduct->stand)
                        <a href="{{ route('products.gypsum.stands.edit', $product->gypsumProduct->stand->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @elseif($product->gypsumProduct && $product->gypsumProduct->vase)
                        <a href="{{ route('products.gypsum.vases.edit', $product->gypsumProduct->vase->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @elseif($product->gypsumProduct && $product->gypsumProduct->statue)
                        <a href="{{ route('products.gypsum.statues.edit', $product->gypsumProduct->statue->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @elseif($product->set)
                        <a href="{{ route('products.sets.edit', $product->set->id) }}" class="text-white text-decoration-none">Изменить</a>
                    @else
                        Нет доступного маршрута для изменения
                    @endif
                </button>

                @if($product->set)
                    <div class="mt-5">
                        <h3>Товары в наборе</h3>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            @foreach($product->set->items as $item)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $item->name ?? 'Без названия' }}</h5>
                                            <p class="card-text">
                                                <strong>Количество:</strong> {{ $item->quantity }} шт.<br>
                                                <strong>Общая стоимость:</strong> {{ number_format($item->cost * $item->quantity, 2, ',', ' ') }} ₽
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-4">
                        <h3>Характеристики</h3>
                        <ul>
                            @foreach($product->specific_attributes as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
