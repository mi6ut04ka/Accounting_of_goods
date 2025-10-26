@extends('layouts.base')

@section('title', "Изменение аромата: {{$aroma->name}}")

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Изменение аромата: {{$aroma->name}}</h1>

            <form action="{{ route('aromas.destroy', $aroma->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот аромат?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
        <form action="{{ route('aromas.update', $aroma->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-input name="name" label="Название" type="text" value="{{ old('name', $aroma->name) }}" />

            <x-input name="description" label="Описание" type="text" optional value="{{ old('description', $aroma->description) }}" />

            <x-input name="top_notes" label="Верхние ноты" type="text" optional value="{{ old('top_notes', $aroma->top_notes) }}" />
            <x-input name="middle_notes" label="Средние ноты" type="text" optional value="{{ old('middle_notes', $aroma->middle_notes) }}" />
            <x-input name="base_notes" label="Базовые ноты" type="text" optional value="{{ old('base_notes', $aroma->base_notes) }}" />

            <button type="submit" class="btn btn-success">Изменить</button>
        </form>
    </div>
@endsection
