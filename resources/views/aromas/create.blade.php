@extends('layouts.base')

@section('title', 'Ароматы')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Добавление аромата</h1>
        <form action="{{ route('aromas.store') }}" method="POST" >
            @csrf
            <x-input name="name" label="Название" type="text"/>

            <x-input name="description" label="Описание" type="text" optional/>

            <x-input name="top_notes" label="Верхние ноты" type="text" optional/>
            <x-input name="middle_notes" label="Средние ноты" type="text" optional/>
            <x-input name="base_notes" label="Базовые ноты" type="text" optional/>

            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>


@endsection
