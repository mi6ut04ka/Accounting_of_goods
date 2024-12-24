@extends('layouts.orders')

@section('title', 'Заказы')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создать новый заказ</h1>

        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-input type="text" label="Заказчик" name="customer_name" />
            <x-input type="date" label="Крайний срок" name="deadline_date" />

            <div id="product-fields">
                <div class="row g-3 mb-3 product-row">
                    <div class="col-sm-10">
                        <select name="products[]" class="form-select">
                            <option value="">Выберите товар</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} {{$product->price}} руб.</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-1 col-form-label">Количество</label>
                    <div class="col-sm-1">
                        <input type="number" class="form-control" name="quantities[]" min="1" />
                    </div>
                </div>
            </div>
            <label for="note" class="form-label">Комментарий к заказу</label>
            <textarea name="note" class="form-control mb-3"></textarea>

            <button type="button" class="btn btn-secondary" id="add-product-button">Добавить товар</button>
            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>

    <script>
        document.getElementById('add-product-button').addEventListener('click', function () {
            const productFields = document.getElementById('product-fields');

            // Создаем новую строку с выбором товара и количеством
            const newRow = document.createElement('div');
            newRow.classList.add('product-row');

            newRow.innerHTML = `
                <div class="order-item row g-3 mb-3">
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
