@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row">
        @if(request()->get('page') == 1 || !request()->has('page'))
            <x-add-card :link="route('products.statuettes.create')"/>
        @endif
        @foreach($statuettes as $product)
            <x-product.card :product="$product" :route="'statuettes'"/>
        @endforeach
        {{$statuettes->links()}}
    </div>
@endsection
