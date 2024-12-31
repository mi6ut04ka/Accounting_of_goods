@extends('layouts.products')

@section('title', 'Вазы')

@section('content')
    <div class="row justify-content-start">
        <x-add-card :link="route('products.vases.create')"/>
        @foreach($vases as $product)
            <x-product.card :product="$product" :route="'vases'"/>
        @endforeach
        {{$vases->links()}}
    </div>
@endsection
