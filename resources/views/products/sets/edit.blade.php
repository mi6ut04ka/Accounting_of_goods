@extends('layouts.products')

@section('title', 'Редактировать набор')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4 mb-4">{{$set->product->name}}</h1>
            <form action="{{route('products.sets.destroy', $set->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>

        <form action="{{ route('products.sets.update', $set->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-input name="name" label="Название набора" :value="$set->name" type="text"/>
            <x-input name="price" label="Цена набора" :value="$set->product->price" type="number"/>
            <x-input name="in_stock" label="Количество" :value="$set->product->in_stock" type="number"/>
            <x-input name="description" label="Описание" :value="$set->product->description" type="text"/>
            <x-photo-input/>

            <h3>Элементы набора</h3>
            <div id="set-items-container">
                @foreach($set->items as $index => $item)
                    <div class="set-item row mb-3">
                        <div class="col-md-3">
                            <label>Продукт</label>
                            <select name="items[{{ $index }}][product_id]" {{!$item->product_id ? 'disabled' : ''}} class="form-control product-select">
                                <option value="">Выберите продукт</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Название (для стороннего)</label>
                            <input type="text" name="items[{{ $index }}][name]" class="form-control name-input"
                                   value="{{ $item->product_id ? '' : $item->name }}" {{ $item->product_id ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-2">
                            <label>Стоимость</label>
                            <input type="number" name="items[{{ $index }}][cost]" class="form-control cost-input"
                                   value="{{ $item->cost }}" {{ $item->product_id ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-2">
                            <label>Количество</label>
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-set-item">Удалить</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary" id="add-set-item">Добавить элемент</button>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
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
                    <label>Цена</label>
                    <input type="number" name="items[${index}][cost]" class="form-control cost-input">
                </div>
                <div class="col-md-2">
                    <label>Количество</label>
                    <input type="number" name="items[${index}][quantity]" class="form-control">
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
