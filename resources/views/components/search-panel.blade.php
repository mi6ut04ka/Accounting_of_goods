<div class="form-outline position-relative w-100 ms-3" data-mdb-input-init>
    <input type="search" id="search" class="form-control" placeholder="Найти" aria-label="Search" />
    <ul id="results" class="dropdown-menu show mt-2 w-100" style="display: none; max-height: 300px; overflow-y: auto;"></ul>
</div>

<script>
    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search');
        const resultsContainer = document.getElementById('results');

        if (searchInput) {
            const performSearch = async () => {
                const query = searchInput.value;

                if (query.length === 0) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.style.display = 'none';
                    return;
                }

                try {
                    const response = await fetch(`/search?query=${query}`);
                    const products = await response.json();

                    if (products.length === 0) {
                        resultsContainer.innerHTML = '<li class="dropdown-item text-muted text-center">Ничего не найдено</li>';
                        resultsContainer.style.display = 'block';
                        return;
                    }

                    resultsContainer.innerHTML = products.map(product => `
                        <a href="${product.id}" class="link">
                            <li class="dropdown-item">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">${product.name}</span>
                                    <span>Цена: ${product.price} ₽</span>
                                    <span>В наличии: ${product.in_stock}</span>
                                </div>
                            </li>
                        </a>
                    `).join('');
                    resultsContainer.style.display = 'block';
                } catch (error) {
                    console.error('Ошибка при выполнении поиска:', error);
                }
            };

            searchInput.addEventListener('input', debounce(performSearch, 300));
        }

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    });
</script>
