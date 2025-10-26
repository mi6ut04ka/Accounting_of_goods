@extends('layouts.base')

@section('title', 'Ароматы')

@section('content')
    <div class="container">
        <h1 class="mb-4">Ароматы</h1>

        <a href="{{ route('aromas.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Добавить аромат
        </a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Верхние ноты</th>
                <th>Средние ноты</th>
                <th>Базовые ноты</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($aromas as $aroma)
                <tr>
                    <td>{{ $aroma->name }}</td>
                    <td>{{ $aroma->description ?? 'Нет описания' }}</td>
                    <td>{{ $aroma->top_notes ?? '—' }}</td>
                    <td>{{ $aroma->middle_notes ?? '—' }}</td>
                    <td>{{ $aroma->base_notes ?? '—' }}</td>
                    <td>
                        <a href="{{ route('aromas.edit', $aroma->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Изменить
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
