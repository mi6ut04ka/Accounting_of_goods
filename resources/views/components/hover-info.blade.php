@php
    $translations = [
        'name' => 'Название',
        'price' => 'Цена',
        'cost' => 'Себестоимость',
        'description' => 'Описание',
        'in_stock' => 'Количество',
    ];
@endphp
<div class="hover-info">
    <p><strong>Себестоимость, руб:</strong> {{$product->cost}}</p>

    @foreach($product->getAttributes() as $key => $value)
        @if(!Str::contains($key, 'id') && !in_array($key, ['created_at', 'updated_at']))
            <p>
                <strong>
                    {{ isset($translations[$key]) ? $translations[$key] : ucfirst($key) }}:
                </strong>
                {{ $value }}
            </p>
        @endif
    @endforeach

    @foreach($product->attributeValues as $attributeValue)
        <p>
            <strong>{{ $attributeValue->attribute->name }}:</strong>
            {{ $attributeValue->value }}
        </p>
    @endforeach
</div>
