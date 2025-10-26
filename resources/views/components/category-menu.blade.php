<ul class="navbar-nav d-flex justify-content-around flex-wrap w-100 gap-2">
    @foreach($categories as $category)
        @if(!$category->is_final && $category->children->isNotEmpty())
            <li class="nav-item dropdown position-relative">
                <a
                    class="nav-link dropdown-toggle {{ request('category') == $category->id ? 'active' : '' }}"
                    href="#"
                    id="dropdown-{{$category->id}}"
                    role="button"
                    data-dropdown>
                    {{$category->name}}
                </a>
                <ul class="dropdown-menu position-absolute" aria-labelledby="dropdown-{{$category->id}}" style="display: none;">
                    @foreach($category->children as $subcategory)
                        <li>
                            <a
                                href="{{ route('products.index', ['category' => $subcategory->id]) }}"
                                class="dropdown-item {{ request('category') == $subcategory->id ? 'active' : '' }}">
                                {{$subcategory->name}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <a
                    href="{{ route('products.index', ['category' => $category->id]) }}"
                    class="nav-link {{ request('category') == $category->id ? 'active' : '' }}">
                    {{$category->name}}
                </a>
            </li>
        @endif
    @endforeach
</ul>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Получаем все элементы dropdown
        const dropdowns = document.querySelectorAll('[data-dropdown]');

        dropdowns.forEach(dropdown => {
            const parent = dropdown.closest('.dropdown'); // Родительский элемент
            const menu = parent.querySelector('.dropdown-menu'); // Меню dropdown

            // Показать меню
            dropdown.addEventListener('click', function (e) {
                e.preventDefault(); // Отменяем переход по ссылке
                const isVisible = menu.style.display === 'block';

                // Скрыть все открытые меню
                document.querySelectorAll('.dropdown-menu').forEach(item => {
                    item.style.display = 'none';
                });

                // Показать текущее меню
                if (!isVisible) {
                    menu.style.display = 'block';
                }
            });

            // Закрыть меню, если кликнули вне его
            document.addEventListener('click', function (e) {
                if (!parent.contains(e.target)) {
                    menu.style.display = 'none';
                }
            });
        });
    });
</script>
