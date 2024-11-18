<div class="card-wrapper card mb-4 m-3" style="width: 18rem;">
    @if ($product->photos->isNotEmpty())
        <img
            src="{{ asset('storage/' . $product->photos->first()->url) }}"
            class="card-img-top mt-3 product-image"
            alt="Фото продукта">
    @else
        <img
            src="/images/img.png"
            class="card-img-top mt-3 product-image"
            alt="Изображение по умолчанию">
    @endif
    <div class="card-body">
        <h5 class="card-title">
            {{$product->full_name}}
        </h5>
        <div class="column d-flex justify-content-between align-items-center">
            <div class="card-text">В наличии: {{ $product->in_stock }} шт.</div>
            <div
                class="stretched-link-hover"
                data-bs-toggle="modal"
                data-bs-target="#edit-modal-{{ $product->parent_id }}">
                Изменить
            </div>
        </div>
        <x-modalInStoke
            id="edit-modal-{{ $product->parent_id }}"
            :in_stock="$product->in_stock"
            :id_product="$product->parent_id">
        </x-modalInStoke>
        <p class="card-text">Цена: {{(int)$product->price}} рублей</p>
        <div class="d-flex justify-content-between">
            <a href="{{ route("products.$route.edit", $product->id) }}" class="btn btn-light">Изменить</a>
            <button
                class="btn btn-secondary"
                data-bs-toggle="modal"
                data-bs-target="#sold-modal-{{ $product->parent_id }}">
                Продано
            </button>
            <x-modalSold
                id="sold-modal-{{ $product->parent_id }}"
                :id_product="$product->parent_id">
            </x-modalSold>
        </div>
    </div>
        <x-hover-info :product="$product"/>
</div>

