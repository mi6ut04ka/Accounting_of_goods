@extends('layouts.products')

@section('title', 'Подставки')

@section('content')
    <div class="row justify-content-start">
        <x-add-card :link="route('products.stands.create')"/>
        @foreach($stands as $product)
            <x-product.card :product="$product" :route="'stands'"/>
        @endforeach
            {{$stands->links()}}
    </div>
@endsection
