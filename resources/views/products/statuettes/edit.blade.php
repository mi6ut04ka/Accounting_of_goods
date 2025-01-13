@extends('layouts.products')

@section('title', $statuette->full_name)

@section('content')
    <x-product.edit-form :product="$statuette" name="statuettes">
        <x-product.inputs :cost="$statuette->cost" :price="$statuette->price" :in_stock="$statuette->in_stock" :description="$statuette->description"/>
        <x-input :value="$statuette->statue_type" type="text" label="Тип статуэтки" name="statue_type" />
        <x-input :value="$statuette->color" type="text" label="Цвет" name="color" />
        <x-input :value="$statuette->gypsum_weight" type="number" label="Вес гипса, гр" name="gypsum_weight" />
    </x-product.edit-form>
@endsection
