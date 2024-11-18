
@extends('layouts.products')

@section('title', $vase->full_name)

@section('content')
    <x-product.edit-form :product="$vase" name="vases">
        <x-product.inputs :cost="$vase->cost" :price="$vase->price"/>
        <x-input :value="$vase->name" type="text" label="Название" name="name" />
        <x-input :value="$vase->color" type="text" label="Цвет" name="color" />
        <x-input :value="$vase->gypsum_weight" type="number" label="Вес гипса, гр" name="gypsum_weight" />
    </x-product.edit-form>
@endsection
