<!-- resources/views/products/create.blade.php -->

@extends('layouts.products')

@section('title', 'Создать')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создание продукта</h1>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="product_type" class="form-label">Выберите тип продукта:</label>
                <select name="type" id="product_type" class="form-select" required>
                    <option value="">Тип продукта</option>
                    <option value="molded_candles">Формовая свеча</option>
                    <option value="container_candles">Контейнераня свеча</option>
                    <option value="stands">Подставка</option>
                    <option value="vases">Ваза</option>
                    <option value="statuettes">Статуэтка</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary ">Продолжить</button>
        </form>
    </div>

@endsection
