@extends('layouts.orders')

@section('title', 'Заказы')
@section('content')
    <div class="row">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h1>Заказы</h1>
            <a href="{{route('orders.create')}}">
                <button class="btn btn-primary">Создать</button>
            </a>
        </div>
        @foreach($orders as $order)
            <x-orders.card :order="$order"/>
        @endforeach
    </div>
@endsection
