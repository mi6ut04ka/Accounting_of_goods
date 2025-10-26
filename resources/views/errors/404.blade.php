@extends('layouts.base')

@section('content')
    <h1>Ошибка 404</h1>
    <p>{{ $exception->getMessage() }}</p>
@endsection
