@php
    $translations = [
        'name' => 'Название',
        'wax_weight' => 'Вес воска, г',
        'fragrance' => 'Аромат',
        'fragrance_percentage' => 'Процент аромата, %',
        'volume' => 'Объем, мл',
        'container_color' => 'Цвет контейнера',
        'box_size' => 'Размер коробки',
        'decor' => 'Декор',
        'decor_description' => 'Описание декора',
        'type_of_wax' => 'Тип воска',
        'stand_type' => 'Тип подставки',
        'color' => 'Цвет',
        'gypsum_weight' => 'Вес гипса, г',
        'statue_type' => 'Тип статуи',
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
</div>
