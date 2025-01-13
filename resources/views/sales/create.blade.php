@extends('layouts.base')

@section('title', 'Добавить продажу')

@section('content')
    <h1>Добавить продажу</h1>
    <form action="{{route('sales.store')}}" method="POST">
        @csrf
        @method('POST')
        <x-input name="time_of_sale" label="Время продажи" type="date"/>
        <x-input name="product_name" label="Название товара" type="text"/>
        <x-input name="price" label="Цена за штуку" type="number"/>
        <x-input name="quantity" label="Количество" type="number"/>
    <button type="submit" class="btn btn-success">Добавить</button>
    </form>

@endsection
