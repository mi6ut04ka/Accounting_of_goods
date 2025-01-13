@extends('layouts.products')

@section('title', 'Создать набор')

@section('content')
    <div class="container mt-5">
        <h1>Создать новый набор</h1>

        <form action="{{ route('products.sets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-input name="name" label="Название набора" type="text"/>
            <x-input name="price" label="Цена набора" type="number"/>
            <x-input name="in_stock" label="Количество" type="number"/>
            <x-input name="description" label="Описание" type="text"/>
            <x-photo-input/>

            <h3>Элементы набора</h3>
            <div id="set-items-container">
                <div class="set-item row mb-3">
                    <div class="col-md-3">
                        <label>Продукт</label>
                        <select name="items[0][product_id]" class="form-control product-select">
                            <option value="">Выберите продукт</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Название (для стороннего)</label>
                        <input type="text" name="items[0][name]" class="form-control name-input">
                    </div>
                    <div class="col-md-2">
                        <label>Стоимость</label>
                        <input type="number" name="items[0][cost]" class="form-control cost-input">
                    </div>
                    <div class="col-md-2">
                        <label>Количество</label>
                        <input value="1" type="number" required name="items[0][quantity]" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-set-item">Удалить</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-set-item">Добавить элемент</button>

            <button type="submit" class="btn btn-primary">Создать набор</button>
        </form>
    </div>

    <script>
        document.getElementById('add-set-item').addEventListener('click', function () {
            const container = document.getElementById('set-items-container');
            const index = container.children.length;
            const template = `
            <div class="set-item row mb-3">
                <div class="col-md-3">
                    <label>Продукт</label>
                    <select name="items[${index}][product_id]" class="form-control product-select">
                        <option value="">Выберите продукт</option>
                        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Название (для стороннего)</label>
            <input type="text" name="items[${index}][name]" class="form-control name-input">
                </div>
                <div class="col-md-2">
                    <label>Стоимость</label>
                    <input type="number" name="items[${index}][cost]" class="form-control cost-input">
                </div>
                <div class="col-md-2">
                    <label>Количество</label>
                    <input type="number" required name="items[${index}][quantity]" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-set-item">Удалить</button>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', template);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-set-item')) {
                e.target.closest('.set-item').remove();
            }
        });
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('name-input')) {
                const nameInput = e.target;
                const productSelect = nameInput.closest('.set-item').querySelector('.product-select');

                if (nameInput.value) {
                    productSelect.disabled = true;
                    productSelect.value = '';
                } else {
                    productSelect.disabled = false;
                }
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const select = e.target;
                const nameInput = select.closest('.set-item').querySelector('.name-input');
                const costInput = select.closest('.set-item').querySelector('.cost-input');

                if (select.value) {
                    nameInput.disabled = true;
                    nameInput.value = '';
                    costInput.disabled = true;
                    costInput.value = '';
                } else {
                    nameInput.disabled = false;
                    costInput.disabled = false;
                }
            }
        });
    </script>
@endsection
