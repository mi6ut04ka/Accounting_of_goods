@extends('layouts.base')

@section('optional_navigation')
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container justify-content-center">
            <ul class="navbar-nav justify-content-around w-100 gap-2">
                <x-nav-link class="nav-product" :active="request('status') === 'pending'" title="Новые заказы" link="{{ route('orders.index', ['status' => 'pending']) }}"/>
                <x-nav-link class="nav-product" :active="request('status') === 'processing'" title="В работе" link="{{ route('orders.index', ['status' => 'processing']) }}"/>
                <x-nav-link class="nav-product" :active="request('status') === 'completed'" title="Выполненные" link="{{ route('orders.index', ['status' => 'completed']) }}"/>
                <x-nav-link class="nav-product" :active="request('status') === 'issued'" title="Выданные" link="{{ route('orders.index', ['status' => 'issued']) }}"/>
                <x-nav-link class="nav-product" :active="request('status') === 'cancelled'" title="Отмененные" link="{{ route('orders.index', ['status' => 'cancelled']) }}"/>
            </ul>
        </div>
    </nav>
@endsection
