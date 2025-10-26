@extends('layouts.categories')

@section('title', "Категория {$category->name}")

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4 mb-4">{{ $category->name }}</h1>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-input name="name" label="Название категории" :value="$category->name" type="text"/>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <input value="{{$category->description}}" type="text" id="description" name="description" class="form-control">
            </div>

            <label for="photo" class="form-label">
                Фото категории
            </label>

            <input type="file" name="photo" id="photo" class="form-control" multiple>

            @error('photo')
            <small class="text-danger">{{ $message }}</small>
            @enderror

            <div class="form-check">
                <input type="checkbox" id="is_set" name="is_set" {{$category->is_set ? 'checked' : null}} class="form-check-input">
                <label class="form-check-label" for="is_set">
                    Эта категория является набором
                </label>
            </div>

            <div class="form-check mt-2">
                <input type="checkbox" id="is_final" name="is_final" {{$category->is_final ? 'checked' : null}} class="form-check-input">
                <label class="form-check-label" for="is_final">
                    Конечная категория
                </label>
            </div>

            <div class="form-check mt-2">
                <input type="checkbox" id="is_visible" name="is_visible" {{$category->is_visible ? 'checked' : null}} class="form-check-input">
                <label class="form-check-label" for="is_visible">
                    Видимая категория
                </label>
            </div>

            <div class="mb-3 mt-2">
                <label for="type" class="form-label">Тип категории</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="raw_material" {{ $category->type == 'raw_material' ? 'selected' : '' }}>Сырьё</option>
                    <option value="product" {{ $category->type == 'product' ? 'selected' : '' }}>Продукт</option>
                </select>
            </div>
            <h3 class="mt-4">Атрибуты набора</h3>
            <div id="set-items-container">
                @if ($category->attributes && $category->attributes->count())
                    @foreach ($category->attributes as $attribute)
                        <div class="set-item mb-3 p-3 border rounded" id="attribute-{{ $loop->index }}">
                            <x-input
                                name="attributes[{{ $loop->index }}][name]"
                                label="Название атрибута"
                                :value="$attribute->name"
                                type="text"
                            />
                            <x-select
                                name="attributes[{{ $loop->index }}][type]"
                                label="Тип атрибута"
                                :options="$options"
                                :selected="$attribute->data_type"
                            />
                            @if ($attribute->data_type === 'select')
                                <x-input
                                    name="attributes[{{ $loop->index }}][options]"
                                    label="Опции (через запятую)"
                                    :value="implode(',', $attribute->options ?? [])"
                                    type="text"
                                />
                            @endif
                            <button type="button" class="btn btn-danger mt-2 remove-attribute" data-id="attribute-{{ $loop->index }}">
                                Удалить атрибут
                            </button>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Атрибутов пока нет.</p>
                @endif
            </div>

            <button type="button" class="btn btn-secondary mt-4" id="add-set-item">Добавить элемент</button>
            <button type="submit" class="btn btn-primary mt-4">Сохранить изменения</button>
        </form>
    </div>

    <script>
        document.getElementById('add-set-item').addEventListener('click', function () {
            const container = document.getElementById('set-items-container');
            const index = container.children.length;

            const newItem = `
            <div class="set-item mb-3 p-3 border rounded" id="attribute-${index}">
                <x-input
                    name="attributes[${index}][name]"
                    label="Название атрибута"
                    value=""
                    type="text"
                />
                <div class="mb-3">
                    <label for="attributes_${index}_type" class="form-label">Тип атрибута</label>
                    <select
                        name="attributes[${index}][type]"
                        id="attributes_${index}_type"
                        class="form-select attribute-type-select"
                        data-index="${index}"
                    >
                        <option value="string" selected>Строка</option>
                        <option value="int">Целое число</option>
                        <option value="select">Выбор</option>
                    </select>
                </div>
                <div class="mb-3 options-container d-none" id="options-container-${index}">
                    <label for="attributes_${index}_options" class="form-label">Опции (через запятую)</label>
                    <input
                        type="text"
                        name="attributes[${index}][options]"
                        id="attributes_${index}_options"
                        class="form-control"
                        value=""
                    />
                </div>
                <button type="button" class="btn btn-danger mt-2 remove-attribute" data-id="attribute-${index}">
                    Удалить атрибут
                </button>
            </div>`;

            container.insertAdjacentHTML('beforeend', newItem);
            attachChangeEventToSelect(index);
            attachRemoveEvent();
        });

        function attachChangeEventToSelect(index) {
            const selectElement = document.querySelector(`#attributes_${index}_type`);
            const optionsContainer = document.querySelector(`#options-container-${index}`);

            selectElement.addEventListener('change', function () {
                if (this.value === 'select') {
                    optionsContainer.classList.remove('d-none');
                } else {
                    optionsContainer.classList.add('d-none');
                }
            });
        }

        function attachRemoveEvent() {
            const removeButtons = document.querySelectorAll('.remove-attribute');
            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const attributeId = this.dataset.id;
                    const attributeElement = document.getElementById(attributeId);
                    if (attributeElement) {
                        attributeElement.remove();
                    }
                });
            });
        }
        attachRemoveEvent();
    </script>
@endsection
