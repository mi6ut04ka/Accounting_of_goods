@extends('layouts.base')

@section('title', 'Сырье')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Список сырья</h1>

        <div class="row mb-4 justify-content-between">
            <form method="GET" action="{{ route('raws.index') }}" class="col-md-10 col-lg-10">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Поиск</button>
                </div>
            </form>
            <div class="col-md-2 col-lg-2 text-end">
                <a href="{{ route('raws.create') }}" class="btn btn-success w-100">Создать</a>
            </div>
        </div>

        @if($raws->count())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($raws as $raw)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-img-top text-center bg-light p-3">
                                @if ($raw->photo)
                                    <img src="{{ asset('storage/' . $raw->photo->url) }}" class="img-fluid rounded" alt="Фото продукта" style="max-height: 200px; object-fit: contain;">
                                @else
                                    <img src="/images/img.png" class="img-fluid rounded" alt="Изображение по умолчанию" style="max-height: 200px; object-fit: contain;">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">{{ $raw->name }}</h5>
                                <p class="card-text mb-2">
                                    <strong>Цена:</strong> {{ number_format($raw->price, 2, ',', ' ') }} ₽
                                </p>

                                @if($raw->attributes->count())
                                    <ul class="list-unstyled mb-3">
                                        @foreach($raw->attributes as $attribute)
                                            <li><strong>{{ $attribute->key }}:</strong> {{ $attribute->value }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="mt-auto">
                                    @if($raw->link)
                                        <a href="{{ $raw->link }}" target="_blank" class="btn btn-outline-secondary btn-sm w-100 mb-2">Заказать</a>
                                    @else
                                        <span class="badge bg-secondary d-block text-center py-2 mb-2">Ссылка отсутствует</span>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('raws.edit', $raw->id) }}" class="btn btn-outline-secondary btn-sm w-50">Изменить</a>
                                        <form action="{{ route('raws.destroy', $raw->id) }}" method="POST" style="width: 49%;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Вы уверены, что хотите удалить этот элемент?');">Удалить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">Сырья пока нет.</p>
        @endif
    </div>
@endsection
