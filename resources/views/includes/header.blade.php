<header class="bg-light shadow-sm">
    <div class="navbar navbar-expand-lg navbar-light container py-2">
        <a class="navbar-brand fw-bold" style="font-family: 'Brilliant', sans-serif; font-size: 1.5rem;" href="{{ route('products.molded_candles.index') }}">
            <i class="bi bi-candle"></i> {{ config('app.name') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto d-flex flex-row align-items-center">
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center {{ activeLink('products*') ? 'active' : '' }}" href="{{ route('products.molded_candles.index') }}">
                        <i class="bi bi-box-seam me-2"></i>
                        Продукты
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center {{ activeLink('statistics*') ? 'active' : '' }}" href="{{ route('statistics.index') }}">
                        <i class="bi bi-graph-up me-2"></i>
                        Статистика
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center {{ activeLink('orders*') ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'pending']) }}">
                        <i class="bi bi-cart-check me-2"></i>
                        Заказы
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center {{ activeLink('raws*') ? 'active' : '' }}" href="{{ route('raws.index') }}">
                        <i class="bi bi-box me-2"></i>
                        Сырье
                    </a>
                </li>
            </ul>

            @if(activeLink('products*'))
                <div class="d-flex align-items-center w-100">
                    <x-search-panel id="search-products" class="flex-grow-1" />
                    <span class="badge bg-secondary ms-3">
            <i class="bi bi-cash-stack"></i> <strong>{{ (string)potentialRevenue() }}</strong> руб.
        </span>
                    <span class="badge bg-secondary ms-3">
            <i class="bi bi-boxes"></i> <strong>{{ (string)countProduct() }}</strong> шт.
        </span>
                </div>
            @endif
        </div>
    </div>
    @yield('optional_navigation')
</header>

