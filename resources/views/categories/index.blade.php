@extends('layouts.categories')

@section('title', 'Категории')

@section('content')
    <div class="row justify-content-start">
        <div class="col-md-3 mb-4">
            <div class="card h-100 text-center border-dashed bg-light">
                <div class="card-body d-flex flex-column justify-content-center">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Добавить категорию
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h3>Категории сырья</h3>
    <div class="row justify-content-start">
        @foreach($categories->where('type', 'raw_material') as $category)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        @if($category->photo)
                            <img src="{{ $category->photo->url }}" alt="{{ $category->name }} Фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <img src="default-image-url.jpg" alt="Нет фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;"> <!-- Default image if no photo exists -->
                        @endif

                        <h5 class="card-title text-truncate">{{ $category->name }}</h5>

                        @if($category->attributes->isNotEmpty())
                            <div class="mb-3">
                                <strong>Атрибуты:</strong>
                                <ul class="list-unstyled">
                                    @foreach($category->attributes as $attribute)
                                        <li><i class="bi bi-dot"></i> {{ $attribute->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-2">
                            @if(!$category->is_final)
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-list-nested"></i> Смотреть подкатегории
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-lock"></i> Финальная категория
                                </button>
                            @endif

                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-pencil"></i> Изменить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h3>Категории продуктов</h3>
    <div class="row justify-content-start">
        @foreach($categories->where('type', 'product') as $category)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        @if($category->photo)
                            <img src="{{ asset('https://s3.regru.cloud/aromosa/' . $category->photo->url ) }}" alt="{{ $category->name }} Фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <img src="default-image-url.jpg" alt="Нет фото" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;"> <!-- Default image if no photo exists -->
                        @endif

                        <h5 class="card-title text-truncate">{{ $category->name }}</h5>

                        @if($category->attributes->isNotEmpty())
                            <div class="mb-3">
                                <strong>Атрибуты:</strong>
                                <ul class="list-unstyled">
                                    @foreach($category->attributes as $attribute)
                                        <li><i class="bi bi-dot"></i> {{ $attribute->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-2">
                            @if(!$category->is_final)
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-list-nested"></i> Смотреть подкатегории
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-lock"></i> Финальная категория
                                </button>
                            @endif

                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-pencil"></i> Изменить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
