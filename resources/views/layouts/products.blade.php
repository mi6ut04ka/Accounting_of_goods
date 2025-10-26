@extends('layouts.base')

@section('optional_navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container justify-content-center">
            <x-category-menu :categories="$categories" />
        </div>
    </nav>
    @yield('search')
@endsection
