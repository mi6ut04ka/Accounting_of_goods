@extends('layouts.base')

@section('title', 'Промокоды')

@section('content')
    <div class="container">
        <h1 class="mb-4">Промокоды</h1>

        <a href="{{ route('promo-codes.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Добавить промокод
        </a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Код</th>
                <th>Скидка</th>
                <th>Действует с</th>
                <th>Действует до</th>
                <th>Мин. сумма заказа</th>
                <th>Лимит использований</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($promoCodes as $promoCode)
                <tr>
                    <td>{{ $promoCode->code }}</td>
                    <td>{{ $promoCode->discount }}%</td>
                    <td>{{ $promoCode->start_date->format('d.m.Y') }}</td>
                    <td>{{ $promoCode->end_date->format('d.m.Y') }}</td>
                    <td>{{ $promoCode->min_order_amount ?? '—' }}</td>
                    <td>{{ $promoCode->usage_limit ?? '—' }}</td>
                    <td>
                        <a href="{{ route('promo-codes.edit', $promoCode->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Изменить
                        </a>
                        <form action="{{ route('promo-codes.destroy', $promoCode->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                <i class="bi bi-trash"></i> Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
