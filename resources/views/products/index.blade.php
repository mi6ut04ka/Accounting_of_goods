@extends('layouts.products')

@section('title', 'Продукты')

@section('content')
    <div class="row justify-content-start">
        @if($category)
            <h1>{{$category->name}}</h1>
            <x-add-card :link="route('products.create', ['category' => $category->id])"/>
        @endif
        @foreach($products as $product)
            <x-product.card :product="$product"/>
        @endforeach
    </div>
@endsection
