@extends('layouts.orders')

@section('title', "Изменить заказ")

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4 mb-4">Изменить заказ</h1>
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                  onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ?');"
                  class="mb-0"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить заказ</button>
            </form>
        </div>

        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @method('PUT')
            @csrf

            <x-input type="text" label="Заказчик" :value="$order->customer_name" name="customer_name" />
            <x-input type="date" label="Крайний срок" :value="$order->deadline_date" name="deadline_date" />

            <div id="product-fields">
                @foreach($order->products as $order_product)
                    <div class="order-item row g-3 mb-3">
                        <div class="col-sm-9">
                            <select name="products[]" class="form-select">
                                <option value="{{ $order_product->id }}" selected>{{ $order_product->name }} {{$order_product->price}} руб.</option>
                                @foreach($products as $product)
                                    @if($product->id !== $order_product->id)
                                        <option value="{{ $product->id }}">{{ $product->name }} {{$product->price}} руб.</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <label class="col-sm-1 col-form-label">Количество</label>
                        <div class="col-sm-1">
                            <input type="number" value="{{ $order_product->pivot->quantity }}" class="form-control" name="quantities[]" min="1" />
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-order-item">Удалить</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <label for="note" class="form-label">Комментарий к заказу</label>
            <textarea name="note" class="form-control mb-3">{{ $order->note }}</textarea>

            <button type="button" class="btn btn-secondary" id="add-product-button">Добавить товар</button>
            <button type="submit" class="btn btn-success">Изменить</button>
        </form>
    </div>

    <script>
        document.getElementById('add-product-button').addEventListener('click', function () {
            const productFields = document.getElementById('product-fields');

            const newRow = document.createElement('div');
            newRow.classList.add('order-item', 'row', 'g-3', 'mb-3');

            newRow.innerHTML = `
                <div class="col-sm-9">
                    <select name="products[]" class="form-select">
                        <option value="">Выберите товар</option>
                        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
            </select>
        </div>
        <label class="col-sm-1 col-form-label">Количество</label>
        <div class="col-sm-1">
            <input type="number" class="form-control" name="quantities[]" min="1" />
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-order-item">Удалить</button>
        </div>
`;

            productFields.appendChild(newRow);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-order-item')) {
                e.target.closest('.order-item').remove();
            }
        });
    </script>
@endsection
