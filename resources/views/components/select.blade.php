@props(['selected' => '', 'label' => '', 'name' => '', 'class' => '', 'options' => ''])
<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-select {{ $class ?? '' }}"
        {{$attributes}}
    >
        @foreach($options as $key => $value)
            <option
                value="{{ $key }}"
                {{ (string)$key === (string)$selected ? 'selected' : '' }}
            >
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
