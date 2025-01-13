@extends('layouts.base')

@section('title', 'Добавить сырьё')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Добавить новое сырьё</h1>
        <form action="{{ route('raws.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-input name="name" label="Название" type="text"/>

            <x-input name="price" label="Цена" type="number"/>

            <x-input name="link" label="Ссылка" type="url" :optional="true"/>

            <x-photo-input/>

            <div id="raw-attributes-fields" class="mb-4"></div>

            <button type="button" class="btn btn-secondary" id="add-raw-button">Добавить атрибут</button>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="{{ route('raws.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('add-raw-button').addEventListener('click', function () {
            const rawsFields = document.getElementById('raw-attributes-fields');

            const newRow = document.createElement('div');
            newRow.classList.add('row', 'g-3', 'mb-3', 'raw-attributes-row');

            newRow.innerHTML = `
            <div class="col-md-5">
                <label for="key" class="form-label">Название атрибута</label>
                <input type="text" class="form-control" name="keys[]" placeholder="Например: Цвет" required />
            </div>
            <div class="col-md-5">
                <label for="value" class="form-label">Значение</label>
                <input type="text" class="form-control" name="values[]" placeholder="Например: Красный" required />
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-attribute-button">Удалить</button>
            </div>
        `;
            rawsFields.appendChild(newRow);

            newRow.querySelector('.remove-attribute-button').addEventListener('click', function () {
                newRow.remove();
            });
        });
    </script>
@endsection
