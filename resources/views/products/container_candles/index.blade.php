@extends('layouts.products')

@section('title', 'Контейнерные свечи')

@section('content')
    <div class="row justify-content-start">
        <x-add-card :link="route('products.container_candles.create')"/>
        @foreach($container_candles as $product)
            <x-product.card :product="$product" :route="'container_candles'"/>
        @endforeach
        {{$container_candles->links() }}
    </div>
@endsection
