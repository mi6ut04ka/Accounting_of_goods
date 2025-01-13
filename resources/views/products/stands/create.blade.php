
@extends('layouts.products')

@section('title', 'Новая подставка')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создать подставку</h1>

        <form action="{{ route('products.stands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-product.inputs/>
            <x-input type="text" label="Тип подставки" name="stand_type" />
            <x-input type="text" label="Цвет" name="color" :optional="true" />
            <x-input type="number" label="Вес гипса, гр" name="gypsum_weight" />

            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection
