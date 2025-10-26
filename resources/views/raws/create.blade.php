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

            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="" disabled selected>Выберите категорию</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <x-photo-input/>

            <div id="raw-attributes-fields" class="mb-4"></div>

            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="{{ route('raws.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('category_id').addEventListener('change', function () {
            const categoryId = this.value;
            const attributesContainer = document.getElementById('raw-attributes-fields');

            if (!categoryId) {
                attributesContainer.innerHTML = "";
                return;
            }

            fetch(`/categories/${categoryId}/attributes`)
                .then(response => response.json())
                .then(data => {
                    attributesContainer.innerHTML = "";

                    if (data.length === 0) {
                        attributesContainer.innerHTML = "<p class='text-muted'>У этой категории нет атрибутов.</p>";
                        return;
                    }

                    data.forEach(attr => {
                        let inputField = "";

                        if (attr.data_type === "select") {
                            const options = attr.options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                            inputField = `
                                <select name="attributes[${attr.id}]" class="form-select">
                                    ${options}
                                </select>`;
                        } else {
                            inputField = `<input type="${attr.data_type === 'int' ? 'number' : 'text'}"
                                          class="form-control" name="attributes[${attr.id}]" required />`;
                        }

                        attributesContainer.innerHTML += `
                            <div class="mb-3">
                                <label class="form-label">${attr.name}</label>
                                ${inputField}
                            </div>`;
                    });
                })
                .catch(error => console.error('Ошибка загрузки атрибутов:', error));
        });
    </script>
@endsection
