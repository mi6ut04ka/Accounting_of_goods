
@extends('layouts.products')

@section('title', $molded->full_name)

@section('content')
    <x-product.edit-form :product="$molded" name="molded_candles">
        <x-product.inputs :price="$molded->price" :cost="$molded->cost" :in_stock="$molded->in_stock" :description="$molded->description" />
        <x-input :value="$molded->name" type="text" label="Название свечи" name="name" />
        <x-input :value="$molded->wax_weight" type="number" label="Вес воска, г" name="wax_weight" />
        <x-input :value="$molded->fragrance" type="text" label="Аромат" name="fragrance" :optional="true" />
        <x-input :value="$molded->fragrance_percentage" type="number" label="Процент аромата, %" name="fragrance_percentage" :optional="true"/>
    </x-product.edit-form>
@endsection
