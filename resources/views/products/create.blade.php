@extends('layouts.products')

@section('title', 'Создать продукт')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Создание продукта в категории "{{ $category->name }}"</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">

           <x-product.inputs/>
            <div class="aromas"></div>
            <button type="button" class="btn btn-secondary" id="add-aroma">Добавить аромат</button>
            @if($attributes->isNotEmpty())
                <h3 class="mt-4">Атрибуты категории</h3>
                @foreach($attributes as $attribute)
                    <div class="mb-3">
                        <label for="attribute_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
                        @if($attribute->data_type === 'string')
                            <input
                                type="text"
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}]"
                                class="form-control">
                        @elseif($attribute->data_type === 'int')
                            <input
                                type="number"
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}]"
                                class="form-control">
                        @elseif($attribute->data_type === 'float')
                            <input
                                type="number"
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}]"
                                class="form-control"
                                step="0.01">
                        @elseif($attribute->data_type === 'select' && !empty($attribute->options))
                            <select
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}]"
                                class="form-select">
                                <option value="">Выберите...</option>
                                @foreach($attribute->options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                @endforeach
            @endif

            <button type="submit" class="btn btn-primary">Создать продукт</button>
        </form>
    </div>
    <script>
        const aromasDiv = document.querySelector('.aromas');
        const aromas = @json($aromas);
        document.getElementById('add-aroma').addEventListener('click',() => {
            const newRow = document.createElement('div')
            newRow.innerHTML = `
        <div class="row g-3 mb-3 aromas-row">
            <div class="col-sm-6">
                <select name="aromas[]" class="form-select">
                    <option value="">Выберите аромат</option>
                       ${aromas.map(aroma => `<option value="${aroma.id}">${aroma.name}</option>`).join('')}
                </select>
            </div>
            <label class="col-sm-1 col-form-label">Количество</label>
            <div class="col-sm-1">
                <input type="number" class="form-control" name="quantities[]" min="0" />
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-aroma-item">Удалить</button>
            </div>
         </div>
        `
            aromasDiv.appendChild(newRow)
        })
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-aroma-item')) {
                e.target.closest('.aromas-row').remove();
            }
        });
    </script>
@endsection

