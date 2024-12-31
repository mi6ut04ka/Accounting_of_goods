@extends('layouts.base')

@section('optional_navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container justify-content-center">
            <ul class="navbar-nav d-flex justify-content-around flex-wrap w-100 gap-2">
                <x-nav-link class="nav-product" :active="activeLink('products.molded_candle*')" title="Формовые свечи" link="{{route('products.molded_candles.index')}}"/>
                <x-nav-link class="nav-product" :active="activeLink('products.container_candles*')" title="Контейнерные свечи" link="{{route('products.container_candles.index')}}"/>
                <x-nav-link class="nav-product" :active="activeLink('products.stands*')" title="Подставки" link="{{route('products.stands.index')}}"/>
                <x-nav-link class="nav-product" :active="activeLink('products.statuettes*')" title="Статуэтки" link="{{route('products.statuettes.index')}}"/>
                <x-nav-link class="nav-product" :active="activeLink('products.vases*')" title="Вазы" link="{{route('products.vases.index')}}"/>
                <x-nav-link class="nav-product" :active="activeLink('products.sets*')" title="Наборы" link="{{route('products.sets.index')}}"/>
            </ul>
        </div>
    </nav>
    @yield('search')
@endsection
