@extends('layouts.base')

@section('title', 'Статистика')

@section('content')
    <h1>Статистика</h1>

    <form method="GET" action="{{ route('statistics.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Дата начала</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Дата окончания</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Фильтровать</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped" id="sales-table">
        <thead>
        <tr>
            <th>Товар</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
            <th id="date-header" style="cursor: pointer;">Дата продажи <span id="sort-icon"></span></th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->product->name }}</td>
                <td>{{ number_format($sale->product->price, 2, ',', ' ') }} руб.</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format($sale->product->price * $sale->quantity, 2, ',', ' ') }} руб.</td>
                <td>{{ \Carbon\Carbon::parse($sale->time_of_sale)->format('d.m.Y') }}</td>
                <td>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить эту продажу?');">
                            <i class="fas fa-trash-alt"></i> <!-- Иконка корзины -->
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        <strong>Итого:</strong>
        {{ number_format($sales->sum(function($sale) { return $sale->product->price * $sale->quantity; }), 2, ',', ' ') }} руб.
    </div>
@endsection
