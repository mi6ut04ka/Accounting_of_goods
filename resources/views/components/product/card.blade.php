<div id="product-{{$product->id}}" class="card-wrapper card mb-4 m-3" style="width: 18rem;">
    @if ($product->photos->isNotEmpty())
        @php
            $primaryPhoto = $product->photos->firstWhere('is_primary', true) ?? $product->photos->first();
        @endphp
        <img
            src="{{ asset('https://s3.regru.cloud/aromosa/' . $primaryPhoto->url) }}"
            class="card-img-top mt-3 product-image"
            style="cursor: pointer"
            alt="Фото продукта">
    @else
        <img
            src="/images/img.png"
            class="card-img-top mt-3 product-image"
            style="cursor: pointer"
            alt="Изображение по умолчанию">
    @endif
    <div class="card-body">
        <h5 class="card-title">
            {{$product->name}}
        </h5>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="card-text">Категория: {{$product->category->name}}</span>
        </div>

        @if($product->aromas->isNotEmpty())
            @foreach($product->aromas as $aroma)
                <div class="d-flex align-items-center gap-4">
                    <div class="fw-bold" style="min-width: 100px;">{{$aroma->name}}</div>
                    <input
                        type="number"
                        id="input-stock-{{$product->id}}-aroma-{{$aroma->id}}"
                        class="form-control form-control-sm text-center"
                        value="{{ $aroma->pivot->in_stock }}"
                        style="width: 80px; min-width: 60px;"
                        min="0">
                    <button
                        class="btn btn-outline-primary btn-sm update-stock-button"
                        data-aromaid="{{$aroma->id}}"
                        data-id="{{$product->id}}">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            @endforeach
        @else
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-text">В наличии: <span id="stock-{{$product->id}}">{{ $product->in_stock }}</span> шт.</div>
            </div>
            <div class="d-flex align-items-center mt-2">
                <input
                    type="number"
                    id="input-stock-{{$product->id}}"
                    class="form-control form-control-sm text-center"
                    value="{{ $product->in_stock }}"
                    style="width: 80px;"
                    min="0">
                <button
                    class="btn btn-outline-primary btn-sm ms-2 update-stock-button"
                    data-id="{{$product->id}}">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        @endif

        <p class="card-text mt-3">Цена: {{ number_format($product->price, 2, ',', ' ') }} рублей</p>
        <div class="d-flex justify-content-between">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-light">Изменить</a>
            <button
                class="btn btn-secondary"
                data-bs-toggle="modal"
                data-bs-target="#sold-modal-{{ $product->id }}">
                Продано
            </button>
        </div>
    </div>

    <x-modalSold id="sold-modal-{{ $product->id }}" :id_product="$product->id" :aromas="$product->aromas"></x-modalSold>
    <x-hover-info :product="$product"/>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const updateStock = async (id, newStock, aromaId = null) => {
            try {
                if (isNaN(newStock) || newStock < 0) {
                    alert('Введите корректное количество');
                    return;
                }

                const response = await fetch(`/products/${id}/update-stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ in_stock: newStock, aromaId: aromaId }),
                });

                if (response.ok) {
                    const data = await response.json();
                    if (aromaId) {
                        document.getElementById(`input-stock-${id}-aroma-${aromaId}`).value = data.in_stock;
                    } else {
                        document.getElementById(`stock-${id}`).textContent = data.in_stock;
                        document.getElementById(`input-stock-${id}`).value = data.in_stock;
                    }
                } else {
                    alert('Ошибка обновления количества');
                }
            } catch (error) {
                console.error('Ошибка при обновлении количества:', error);
            }
        };

        document.querySelectorAll('.update-stock-button').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const aromaId = button.dataset.aromaid || null;
                const inputId = aromaId
                    ? `input-stock-${id}-aroma-${aromaId}`
                    : `input-stock-${id}`;

                const input = document.getElementById(inputId);
                if (!input) {
                    console.error(`Не найден input с ID: ${inputId}`);
                    return;
                }

                const newStock = parseInt(input.value);
                if (isNaN(newStock) || newStock < 0) {
                    alert('Введите корректное количество');
                    return;
                }

                updateStock(id, newStock, aromaId);
            });
        });
    });
</script>
