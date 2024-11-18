
@extends('layouts.products')

@section('title', 'Новая формовая свеча')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создать формовую свечу</h1>

        <form action="{{ route('products.molded_candles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-product.inputs/>
            <x-input type="text" label="Название свечи" name="name" />
            <x-input type="number" label="Вес воска, г" name="wax_weight" />
            <x-input type="text" label="Аромат" name="fragrance" />
            <x-input type="number" label="Процент аромата, %" name="fragrance_percentage" />
            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection
