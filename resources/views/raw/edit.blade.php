@extends('layouts.base')

@section('title', 'Редактировать сырьё')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Редактировать сырьё</h1>
        <form action="{{ route('raws.update', $raw->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-input name="name" label="Название" type="text" value="{{ $raw->name }}" />

            <x-input name="price" label="Цена" type="number" value="{{ $raw->price }}" />

            <x-input name="link" label="Ссылка" type="url" value="{{ $raw->link }}" />

            <x-photo-input :url="$raw->photo->url?? ''"/>

            <h4 class="mt-4">Атрибуты</h4>
            <div id="raw-attributes-fields">
                @foreach($raw->attributes as $attribute)
                    <div class="row g-3 mb-3 raw-attributes-row">
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="keys[]" value="{{ $attribute->key }}" placeholder="Название атрибута">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="values[]" value="{{ $attribute->value }}" placeholder="Значение">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger remove-attribute">Удалить</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary" id="add-raw-button">Добавить атрибут</button>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('raws.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Добавление нового атрибута
        document.getElementById('add-raw-button').addEventListener('click', function () {
            const rawsFields = document.getElementById('raw-attributes-fields');

            const newRow = document.createElement('div');
            newRow.classList.add('row', 'g-3', 'mb-3', 'raw-attributes-row');

            newRow.innerHTML = `
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="keys[]" placeholder="Название атрибута">
                </div>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="values[]" placeholder="Значение">
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger remove-attribute">Удалить</button>
                </div>
            `;

            rawsFields.appendChild(newRow);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-attribute')) {
                e.target.closest('.raw-attributes-row').remove();
            }
        });
    </script>
@endsection
