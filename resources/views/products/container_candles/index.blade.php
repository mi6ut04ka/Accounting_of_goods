@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row">
        @if(request()->get('page') == 1 || !request()->has('page'))
            <x-add-card :link="route('products.container_candles.create')"/>
        @endif
        @foreach($container_candles as $product)
            <x-product.card :product="$product" :route="'container_candles'"/>
        @endforeach
        {{$container_candles->links() }}
    </div>
@endsection
