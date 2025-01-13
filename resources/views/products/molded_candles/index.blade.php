@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row justify-content-start">
        <x-add-card :link="route('products.molded_candles.create')"/>
    @foreach($molded_candles as $product)
            <x-product.card :product="$product" :route="'molded_candles'"/>
    @endforeach
        {{ $molded_candles->links() }}
    </div>
@endsection
