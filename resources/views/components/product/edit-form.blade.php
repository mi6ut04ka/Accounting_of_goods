<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4 mb-4">{{$product->full_name}}</h1>
        <form action="{{route('products.destroy', $product->parent_id)}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>

    <form action="{{ route("products.{$name}.update", $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{$slot}}
        <button type="submit" class="btn btn-success">Изменить</button>
    </form>
</div>
