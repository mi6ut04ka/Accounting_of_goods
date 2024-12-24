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
                        data-bs-target="#sold-modal-{{ $product->parent_id }}">
                        Продано
                    </button>
                    <x-modalSold
                        id="sold-modal-{{ $product->parent_id }}"
                        :id_product="$product->parent_id">
                    </x-modalSold>
                </div>
        </div>

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
@endsection
