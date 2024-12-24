@props(['label' => '', 'name' => '', 'type' => 'text', 'value' => '', 'optional' => false])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>

    @if ($name === 'description')
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            class="form-control"
            {{ $optional ? '' : 'required' }}>{{ $value ?: old($name) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            class="form-control"
            value="{{ $value ?: old($name) }}"
            {{ $optional ? '' : 'required' }}>
    @endif

    @error($name)
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
