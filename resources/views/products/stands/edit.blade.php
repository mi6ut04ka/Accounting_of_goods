@extends('layouts.products')

@section('title', $stand->full_name)

@section('content')
    <x-product.edit-form :product="$stand" name="stands">
        <x-product.inputs :price="$stand->price" :cost="$stand->cost"/>
        <x-input :value="$stand->stand_type" type="text" label="Тип подставки" name="stand_type" />
        <x-input :value="$stand->color" type="text" label="Цвет" name="color" />
        <x-input :value="$stand->gypsum_weight" type="number" label="Вес гипса, гр" name="gypsum_weight" />
    </x-product.edit-form>
@endsection
