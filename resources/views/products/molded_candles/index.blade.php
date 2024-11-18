@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row">
        @if(request()->get('page') == 1 || !request()->has('page'))
            <x-add-card :link="route('products.molded_candles.create')"/>
        @endif
    @foreach($molded_candles as $product)
            <x-product.card :product="$product" :route="'molded_candles'"/>
    @endforeach
        {{ $molded_candles->links() }}
    </div>
@endsection
