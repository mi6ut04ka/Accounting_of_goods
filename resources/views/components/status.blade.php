@props(['type' => 'success', 'message' => ''])

@php
    // Определяем класс Bootstrap в зависимости от типа
    $alertClass = $type === 'error' ? 'alert-danger' : 'alert-success';
@endphp

<div {{ $attributes->merge(['class' => "alert $alertClass alert-dismissible fade show"]) }} role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
