<div class="card mb-5">
    <div class="card-header d-flex justify-content-between
@              @switch($order->order_status)
                    @case('pending')bg-warning @break
                    @case('processing')bg-secondary @break
                    @case('completed')bg-success @break
                    @case('issued')bg-info @break
                    @case('cancelled')bg-danger @break
                @endswitch">
        <span><strong>Имя заказчика:</strong> {{ $order->customer_name }}</span>
        <span>
            <strong class="text-white">Статус:</strong>
            <form
                action="{{ route('orders.update', $order->id) }}"
                method="POST"
                id="status-form-{{ $order->id }}"
                style="display: none;"
                class="d-inline"
            >
                @csrf
                @method('PATCH')
                <select
                    name="order_status"
                    class="form-select d-inline w-auto"
                    onchange="this.form.submit()"
                >
                    <option value="pending" @if($order->order_status == 'pending') selected @endif>принят</option>
                    <option value="processing" @if($order->order_status == 'processing') selected @endif>в процессе</option>
                    <option value="completed" @if($order->order_status == 'completed') selected @endif>выполнен</option>
                    <option value="issued" @if($order->order_status == 'issued') selected @endif>отдан</option>
                    <option value="cancelled" @if($order->order_status == 'cancelled') selected @endif>отменен</option>
                </select>
            </form>
        </span>
    </div>
    <div class="card-body">
        <h5 class="card-title">Товары</h5>
        <table class="table">
            <thead>
            <tr>
                <th>Название</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Итого</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->pivot->price, 2, ',', ' ') }} руб.</td>
                    <td>{{ number_format($product->pivot->price * $product->pivot->quantity, 2, ',', ' ') }} руб.</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="text-end">Общая сумма:</th>
                <th>
                    {{ number_format($order->products->sum(fn($product) => $product->pivot->price * $product->pivot->quantity), 2, ',', ' ') }} руб.
                </th>
            </tr>
            </tfoot>
        </table>
        <p class="card-text"><strong>Дедлайн:</strong> {{ \Carbon\Carbon::parse($order->deadline_date)->format('d.m.Y') }}</p>
        <p class="card-text"><strong>Комментарий:</strong> {{ $order->note }}</p>
    </div>
    <div class="card-footer text-muted">
        Дата создания заказа: {{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}
    </div>
</div>
