
@extends('layouts.products')

@section('title', 'Новая ваза')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создать вазу</h1>

        <form action="{{ route('products.vases.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-product.inputs/>
            <x-input type="text" label="Название" name="name" />
            <x-input type="text" label="Цвет" name="color" />
            <x-input type="number" label="Вес гипса, гр" name="gypsum_weight" />

            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection
