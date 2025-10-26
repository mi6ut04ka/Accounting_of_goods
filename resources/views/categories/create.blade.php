@extends('layouts.categories')

@section('title', $parent ? 'Создать подкатегорию для ' . $parent->name : 'Создать категорию')

@section('content')
    <h1>
        {{ $parent ? 'Создать подкатегорию для ' . $parent->name : 'Создать новую категорию' }}
    </h1>

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $parent->id ?? '' }}">

        <div class="mb-3">
            <label for="name" class="form-label">Название категории</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input type="text" id="description" name="description" class="form-control">
        </div>

        <label for="photo" class="form-label">
            Фото категории
        </label>

        <input type="file" name="photo" id="photo" class="form-control" multiple>

        @error('photo')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        <div class="form-check">
            <input type="checkbox" id="is_set" name="is_set" class="form-check-input">
            <label class="form-check-label" for="is_set">
                Эта категория является набором
            </label>
        </div>

        <div class="form-check mt-2">
            <input type="checkbox" id="is_final" name="is_final" class="form-check-input">
            <label class="form-check-label" for="is_final">
                Конечная категория
            </label>
        </div>

        <div class="form-check mt-2">
            <input type="checkbox" id="is_visible" name="is_visible" checked class="form-check-input">
            <label class="form-check-label" for="is_visible">
                Видимая категория
            </label>
        </div>

        <div class="mb-3 mt-2">
            <label for="type" class="form-label">Тип категории</label>
            <select id="type" name="type" class="form-control" required>
                <option value="product">Продукт</option>
                <option value="raw_material">Сырьё</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Сохранить</button>
    </form>
@endsection
