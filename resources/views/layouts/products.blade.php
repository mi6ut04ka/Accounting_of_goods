@extends('layouts.base')

@section('optional_navigation')
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container justify-content-center">
                <ul class="navbar-nav justify-content-around w-100 gap-2">
                    <x-nav-link :active="activeLink('products.molded_candle*')" title="Формовые свечи" link="{{route('products.molded_candles.index')}}"/>
                    <x-nav-link :active="activeLink('products.container_candles*')" title="Контейнерные свечи" link="{{route('products.container_candles.index')}}"/>
                    <x-nav-link :active="activeLink('products.stands*')" name="products.stands" title="Подставки" link="{{route('products.stands.index')}}"/>
                    <x-nav-link :active="activeLink('products.statuettes*')" title="Статуэтки" link="{{route('products.statuettes.index')}}"/>
                    <x-nav-link :active="activeLink('products.vases*')" title="Вазы" link="{{route('products.vases.index')}}"/>
                </ul>
            </div>
        </nav>
@endsection


