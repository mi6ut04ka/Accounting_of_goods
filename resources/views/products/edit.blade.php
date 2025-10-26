@extends('layouts.products')

@section('title', 'Редактировать продукт')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Редактирование продукта "{{ $product->name }}"</h1>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" value="{{ $product->category_id }}">

            <x-product.inputs
                :name="$product->name"
                :price="$product->price"
                :cost="$product->cost"
                :in_stock="$product->in_stock"
                :description="$product->description"
                :urls="$product->photos ?? ''"
            />

            <div class="aromas"></div>
            <button type="button" class="btn btn-secondary" id="add-aroma">Добавить аромат</button>
            <h3 class="mt-4">Атрибуты категории</h3>
            @foreach($attributes as $attribute)
                <div class="mb-3">
                    <label for="attribute_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
                    @php
                        $existingValue = $product->attributeValues->where('attribute_id', $attribute->id)->first()->value ?? '';
                    @endphp
                    @if($attribute->data_type === 'string')
                        <input
                            type="text"
                            id="attribute_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            class="form-control"
                            value="{{ $existingValue }}">
                    @elseif($attribute->data_type === 'int')
                        <input
                            type="number"
                            id="attribute_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            class="form-control"
                            value="{{ $existingValue }}">
                    @elseif($attribute->data_type === 'float')
                        <input
                            type="number"
                            id="attribute_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            class="form-control"
                            step="0.01"
                            value="{{ $existingValue }}">
                    @elseif($attribute->data_type === 'select' && !empty($attribute->options))
                        <select
                            id="attribute_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            class="form-select">
                            <option value="">Выберите...</option>
                            @foreach($attribute->options as $option)
                                <option value="{{ $option }}" {{ $option == $existingValue ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
    <script>
        const aromasDiv = document.querySelector('.aromas');
        const curAromas = @json($product->aromas);
        const aromas = @json($aromas);
        curAromas.forEach(aroma => {
            const curAromaRow = document.createElement('div')
            curAromaRow.innerHTML = `
            <div class="row g-3 mb-3 aromas-row">
            <div class="col-sm-6">
                <select name="aromas[]" class="form-select">
                    <option value="${aroma.id}">${aroma.name}</option>
                       ${aromas.map(aroma => `<option value="${aroma.id}">${aroma.name}</option>`).join('')}
                </select>
            </div>
            <label class="col-sm-1 col-form-label">Количество</label>
            <div class="col-sm-1">
                <input type="number" class="form-control" name="quantities[]" min="0" value="${aroma.pivot.in_stock}" />
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-aroma-item">Удалить</button>
            </div>
         </div>
            `
            aromasDiv.appendChild(curAromaRow)
        })
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
