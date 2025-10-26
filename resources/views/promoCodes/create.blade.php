@extends('layouts.base')

@section('title', 'Промокод')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Добавление промокода</h1>
        <form action="{{ route('promo-codes.store') }}" method="POST">
            @csrf
            <x-input name="code" label="Промокод" type="text"/>

            <x-input name="discount" label="Скидка, в %" type="number"/>

            <x-input name="start_date" label="Дата начала" type="date"/>
            <x-input name="end_date" label="Дата окончания" type="date"/>

            <div class="form-check mt-2">
                <input type="checkbox" id="is_active" name="is_active" class="form-check-input">
                <label class="form-check-label" for="is_active">
                    Активный
                </label>
            </div>

            <x-input name="min_order_amount" label="Минимальная сумма для заказа" type="number"/>

            <div class="mb-3">
                <label for="applicable_categories" class="form-label">Для каких категорий</label>
                <select name="applicable_categories[]" id="applicable_categories" class="form-control" multiple>
                    <option value="all">Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <x-input name="usage_limit" label="Сколько раз можно использовать" type="number"/>

            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
@endsection
