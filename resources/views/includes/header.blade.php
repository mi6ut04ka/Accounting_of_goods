<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" style="font-family: 'Brilliant', sans-serif " href="{{route('products.molded_candles.index')}}">{{config('app.name')}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{activeLink('products*')? 'active': ''}}" href="{{route('products.molded_candles.index')}}">Продукты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{activeLink('statistics*')? 'active': ''}}" href="{{route('statistics.index')}}">Статистика</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{activeLink('orders*')? 'active': ''}}" href="{{route('orders.index', ['status' => 'pending'])}}">Заказы</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@yield('optional_navigation')


