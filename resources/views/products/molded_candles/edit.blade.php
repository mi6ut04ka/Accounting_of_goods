
@extends('layouts.products')

@section('title', $molded->full_name)

@section('content')
    <x-product.edit-form :product="$molded" name="molded_candles">
        <x-product.inputs :price="$molded->candle->product->price" :cost="$molded->candle->product->cost" />
        <x-input :value="$molded->name" type="text" label="Название свечи" name="name" />
        <x-input :value="$molded->wax_weight" type="number" label="Вес воска, г" name="wax_weight" />
        <x-input :value="$molded->fragrance" type="text" label="Аромат" name="fragrance" />
        <x-input :value="$molded->fragrance_percentage" type="number" label="Процент аромата, %" name="fragrance_percentage" />
    </x-product.edit-form>
@endsection
