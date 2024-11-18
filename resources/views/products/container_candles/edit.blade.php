@extends('layouts.products')

@section('title', $containerCandle->full_name)

@section('content')
    <x-product.edit-form :product="$containerCandle" name="container_candles">
        <x-product.inputs :price="$containerCandle->price" :cost="$containerCandle->cost"/>
        <x-input :value="$containerCandle->volume" type="number" label="Объем, мл" name="volume" />
        <x-input :value="$containerCandle->fragrance" type="text" label="Аромат" name="fragrance" />
        <x-input :value="$containerCandle->fragrance_percentage" type="number" label="Процент аромата, %" name="fragrance_percentage" />
        <x-input :value="$containerCandle->container_color" type="text" label="Цвет контейнера" name="container_color" />
        <x-input :value="$containerCandle->box_size" type="text" label="Размер коробки" name="box_size" />
        <label class="form-label mb-3" for="decor">Декор</label>
        <input type="checkbox" name="decor" value="{{$containerCandle->decor}}" />
        <select  name="type_of_wax" id="type_of_wax" class="form-select mb-3">
            <option value="{{$containerCandle->type_of_wax}}">{{$containerCandle->type_of_wax == 'soy'? 'Cоевый' : 'Кокосовый' }}</option>
            <option value="soy">Соевый</option>
            <option value="coconut">Кокосовый</option>
        </select>
    </x-product.edit-form>
@endsection
