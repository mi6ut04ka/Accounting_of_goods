@extends('layouts.base')

@section('title', 'Редактирование промокода')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1 class="mt-4 mb-4">Редактирование промокода</h1>
        <form action="{{ route('promo-codes.update', $promoCode->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-input name="code" label="Промокод" type="text" value="{{ old('code', $promoCode->code) }}"/>

            <x-input name="discount" label="Скидка, в %" type="number" value="{{ old('discount', $promoCode->discount) }}"/>

            <x-input name="start_date" label="Дата начала" type="date" value="{{ old('start_date', $promoCode->start_date->format('Y-m-d')) }}"/>
            <x-input name="end_date" label="Дата окончания" type="date" value="{{ old('end_date', $promoCode->end_date->format('Y-m-d')) }}"/>

            <div class="form-check mt-2">
                <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
                    {{ old('is_active', $promoCode->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Активный
                </label>
            </div>

            <x-input name="min_order_amount" label="Минимальная сумма для заказа" type="number"
                     value="{{ old('min_order_amount', $promoCode->min_order_amount) }}"/>

            <div class="mb-3">
                <label for="applicable_categories" class="form-label">Для каких категорий</label>
                <select name="applicable_categories[]" id="applicable_categories" class="form-control" multiple>
                    <option value="all" {{ in_array('all', json_decode($promoCode->applicable_categories, true) ?? []) ? 'selected' : '' }}>Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, json_decode($promoCode->applicable_categories, true) ?? []) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-input name="usage_limit" label="Сколько раз можно использовать" type="number"
                     value="{{ old('usage_limit', $promoCode->usage_limit) }}"/>

            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
