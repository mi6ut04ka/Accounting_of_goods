@extends('layouts.base')

@section('title', 'Статистика')

@section('content')
    <h1>Статистика</h1>

    <div class="row align-items-center mb-4">
        <!-- Форма фильтрации -->
        <div class="col-lg-6 mb-3">
            <form method="GET" action="{{ route('statistics.index') }}" class="row gx-3 gy-2 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Дата начала</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Дата окончания</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Фильтровать</button>
                </div>
            </form>
        </div>

        <div class="col-lg-2 mt-3 text-center text-lg-start">
            <a href="{{route('sales.create')}}" class="btn btn-success w-100">Добавить продажу</a>
        </div>

        <div class="col-lg-2 text-center text-lg-start">
            <strong>Итого:</strong>
            <div class="fw-bold text-primary">
                {{ number_format($sales->sum(function($sale) { return $sale->price * $sale->quantity; }), 2, ',', ' ') }} руб.
            </div>
        </div>
        <div class="col-lg-2 text-center text-lg-start">
            <strong>Всего продано:</strong>
            <div class="fw-bold text-primary">
                {{ $sales->sum(function($sale) { return $sale->quantity; }) }} шт.
            </div>
        </div>
    </div>

    </div>
    <table class="table table-bordered table-striped container" id="sales-table">
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
                <td>{{ $sale->product->name?? $sale->product_name }}</td>
                <td>{{ number_format($sale->price, 2, ',', ' ') }} руб.</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format($sale->price * $sale->quantity, 2, ',', ' ') }} руб.</td>
                <td>{{ \Carbon\Carbon::parse($sale->time_of_sale)->format('d.m.Y') }}</td>
                <td>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить эту продажу?');">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

