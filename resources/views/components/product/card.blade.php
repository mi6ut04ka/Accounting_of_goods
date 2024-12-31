<div id="{{$product->parent_id}}" class="card-wrapper card mb-4 m-3" style="width: 18rem;">
    @if ($product->photos->isNotEmpty())
        <img
            src="{{ asset('storage/' . $product->photos->first()->url) }}"
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
            {{$product->full_name}}
        </h5>
        <div class="column d-flex justify-content-between align-items-center">
            <div class="card-text">В наличии: <span id="stock-{{$product->parent_id}}">{{ $product->in_stock }}</span> шт.</div>
        </div>
        <div class="d-flex align-items-center mt-2">
            <input
                type="number"
                id="input-stock-{{$product->parent_id}}"
                class="form-control form-control-sm text-center"
                value="{{ $product->in_stock }}"
                style="width: 60px;"
                min="0">
        </div>
        <p class="card-text mt-3">Цена: {{(int)$product->price}} рублей</p>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const updateStock = async (id, newStock) => {
            try {
                const response = await fetch(`/products/${id}/update-stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ in_stock: newStock }),
                });

                if (response.ok) {
                    const data = await response.json();
                    document.getElementById(`stock-${id}`).textContent = data.in_stock;
                    document.getElementById(`input-stock-${id}`).value = data.in_stock;
                } else {
                    alert('Ошибка обновления количества');
                }
            } catch (error) {
                console.error('Ошибка при обновлении количества:', error);
            }
        };
        document.querySelectorAll('input[id^="input-stock-"]').forEach(input => {
            input.addEventListener('change', (e) => {
                const id = input.id.split('-')[2];
                let currentValue = Math.max(0, parseInt(input.value) || 0);
                input.value = currentValue;
                updateStock(id, currentValue);
            });
        });
    });
</script>

