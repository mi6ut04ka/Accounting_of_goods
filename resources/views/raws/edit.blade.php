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

            <x-photo-input :url="$raw->photo->url ?? ''"/>

            <h4 class="mt-4">Атрибуты</h4>
            <div id="raw-attributes-fields">
                @foreach($raw->attributeValues as $attributeValue)
                    @php
                        // Получаем атрибут и его опции, если они есть
                        $attribute = $attributeValue->attribute;
                        $options = is_string($attribute->options) ? json_decode($attribute->options, true) : (array) $attribute->options;
                    @endphp
                    <div class="row g-3 mb-3 raw-attributes-row">
                        <div class="col-sm-5">
                            <input type="text" class="form-control" value="{{ $attribute->name }}" disabled placeholder="Название атрибута">
                        </div>
                        <div class="col-sm-5">
                            @if($attribute->data_type === 'select')
                                <select name="values[{{ $attributeValue->id }}]" class="form-select" required>
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" @if($attributeValue->value === $option) selected @endif>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" name="values[{{ $attributeValue->id }}]" value="{{ $attributeValue->value }}" placeholder="Значение">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('raws.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection
