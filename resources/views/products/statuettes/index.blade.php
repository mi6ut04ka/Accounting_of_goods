@extends('layouts.products')

@section('title', 'Статуэтки')

@section('content')
    <div class="row justify-content-start">
        <x-add-card :link="route('products.statuettes.create')"/>
        @foreach($statuettes as $product)
            <x-product.card :product="$product" :route="'statuettes'"/>
        @endforeach
        {{$statuettes->links()}}
    </div>
@endsection
