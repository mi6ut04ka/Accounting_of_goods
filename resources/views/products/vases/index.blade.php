@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row">
        @if(request()->get('page') == 1 || !request()->has('page'))
            <x-add-card :link="route('products.vases.create')"/>
        @endif
        @foreach($vases as $product)
            <x-product.card :product="$product" :route="'vases'"/>
        @endforeach
        {{$vases->links()}}
    </div>
@endsection
