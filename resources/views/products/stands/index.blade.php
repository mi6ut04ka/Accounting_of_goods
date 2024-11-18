@extends('layouts.products')

@section('title', 'Формовые свечи')

@section('content')
    <div class="row">
        @if(request()->get('page') == 1 || !request()->has('page'))
            <x-add-card :link="route('products.stands.create')"/>
        @endif
        @foreach($stands as $product)
            <x-product.card :product="$product" :route="'stands'"/>
        @endforeach
            {{$stands->links()}}
    </div>
@endsection
