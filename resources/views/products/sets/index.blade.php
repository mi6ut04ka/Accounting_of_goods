@extends('layouts.products')

@section('title', 'Наборы продуктов')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Наборы продуктов</h1>

        <div class="text-end">
            <a href="{{ route('products.sets.create') }}" class="btn btn-success mb-4">Добавить набор</a>
        </div>

        @if($sets->count())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($sets as $set)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-img-top text-center bg-light p-3">
                                @if ($set->product->photos->isNotEmpty())
                                    <img
                                        src="{{ asset('storage/' . $set->product->photos->first()->url) }}"
                                        alt="Фото набора"
                                        class="img-fluid rounded"
                                        style="max-height: 200px; object-fit: contain;">
                                @else
                                    <img
                                        src="/images/img.png"
                                        alt="Изображение по умолчанию"
                                        class="img-fluid rounded"
                                        style="max-height: 200px; object-fit: contain;">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">{{ $set->product->name }}</h5>
                                <p class="card-text mb-2"><strong>Цена набора:</strong> {{ number_format($set->product->price, 2, ',', ' ') }} руб.</p>
                                <p class="card-text mb-2"><strong>Себестоимость:</strong> {{ number_format($set->product->cost, 2, ',', ' ') }} руб.</p>
                                <p class="card-text"><strong>Товаров в наличии:</strong> {{ $set->product->in_stock }}</p>
                                <p class="card-text"><strong>Количество товаров:</strong> {{ $set->items->count() }}</p>

                                @if($set->items->count())
                                    <h6 class="mt-3">Товары в наборе:</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($set->items as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <span class="text-truncate" style="max-width: 150px;">
                                                    {{ $item->name ?? 'Без названия' }}
                                                    <br><small class="text-muted">({{ $item->quantity }} шт.)</small>
                                                </span>
                                                <span>{{ number_format($item->cost * $item->quantity, 2, ',', ' ') }} руб.</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Набор пуст.</p>
                                @endif

                                <div class="mt-auto d-flex justify-content-between align-items-center pt-3">
                                    <a href="{{ route('products.sets.edit', $set->id) }}" class="btn btn-light">Изменить</a>
                                    <button
                                        class="btn btn-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#sold-modal-{{ $set->product->id }}">
                                        Продано
                                    </button>
                                    <x-modalSold
                                        id="sold-modal-{{ $set->product->id }}"
                                        :id_product="$set->product->id">
                                    </x-modalSold>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">Наборов продуктов пока нет.</p>
        @endif
    </div>
@endsection
