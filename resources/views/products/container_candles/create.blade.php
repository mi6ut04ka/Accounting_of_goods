
@extends('layouts.products')

@section('title', 'Новая контейнерная свеча')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создать контейнерную свечу</h1>

        <form action="{{ route('products.container_candles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-product.inputs/>
            <x-input type="number" label="Объем, мл" name="volume" />
            <x-input type="text" label="Аромат" name="fragrance" />
            <x-input type="number" label="Процент аромата, %" name="fragrance_percentage" />
            <x-input type="text" label="Цвет контейнера" name="container_color" />
            <x-input type="text" label="Размер коробки" name="box_size" />
            <x-input type="text" label="Декор" name="decor_description" />
            <select name="type_of_wax" id="type_of_wax" class="form-select mb-3">
                <option value="">Тип воска</option>
                <option value="soy">Соевый</option>
                <option value="coconut">Кокосовый</option>
            </select>

            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection
