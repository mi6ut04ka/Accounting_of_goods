@extends('layouts.categories')

@section('title', isset($category) ? $category->name : 'Категории')

@section('content')
    <div class="container">
        <h1 class="mb-4">{{ isset($category) ? $category->name : 'Категории' }}</h1>

        <div class="row justify-content-start">
            <div class="col-md-3 mb-4">
                <div class="card h-100 text-center border-dashed bg-light">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <a href="{{ isset($category) ? route('categories.create.custom', $category->id) : route('categories.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Добавить {{ isset($category) ? 'подкатегорию' : 'категорию' }}
                        </a>
                    </div>
                </div>
            </div>

            @foreach(isset($category) ? $category->children : $categories as $categoryItem)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            @if($categoryItem->photo)
                                <img src="{{ asset('https://s3.regru.cloud/aromosa/' . $categoryItem->photo->url)  }}" alt="{{ $categoryItem->name }} Фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <img src="default-image-url.jpg" alt="Нет фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                            @endif

                            <h5 class="card-title text-truncate">{{ $categoryItem->name }}</h5>

                            @if($categoryItem->attributes->isNotEmpty())
                                <div class="mb-3">
                                    <strong>Атрибуты:</strong>
                                    <ul class="list-unstyled">
                                        @foreach($categoryItem->attributes as $attribute)
                                            <li><i class="bi bi-dot"></i> {{ $attribute->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="d-flex flex-column gap-2">
                                @if(!$categoryItem->is_final)
                                    <a href="{{ route('categories.show', $categoryItem->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-list-nested"></i> Смотреть подкатегории
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-lock"></i> Финальная категория
                                    </button>
                                @endif

                                <a href="{{ route('categories.edit', $categoryItem->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i> Изменить
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
