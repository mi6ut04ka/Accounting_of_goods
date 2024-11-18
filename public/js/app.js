document.addEventListener('DOMContentLoaded', () => {
    const cardwrapper = document.querySelectorAll('.card-wrapper');

    cardwrapper.forEach((card) => {
        const info = card.querySelector('.hover-info');
        const image = card.querySelector('.product-image')

        image.addEventListener('click', () => {
            info.style.opacity = '1';
            info.style.visibility = 'visible';
        });

        info.addEventListener('click', () => {
            info.style.opacity = '0';
            info.style.visibility = 'hidden';
        });
    });

    let sortOrder = 'desc';  // Начальный порядок сортировки
    const sortIcon = document.getElementById('sort-icon');
    const dateHeader = document.getElementById('date-header');

    // Обработчик клика на заголовок для сортировки
    dateHeader.addEventListener('click', function() {
        // Переключаем порядок сортировки
        sortOrder = sortOrder === 'desc' ? 'asc' : 'desc';

        // Обновляем иконку сортировки
        sortIcon.textContent = sortOrder === 'desc' ? '↓' : '↑';

        // Получаем все строки таблицы
        const rows = Array.from(document.querySelectorAll('#sales-table tbody tr'));

        // Сортируем строки
        rows.sort((rowA, rowB) => {
            const dateA = new Date(rowA.cells[4].textContent.split('.').reverse().join('-')); // Преобразуем дату в формат YYYY-MM-DD
            const dateB = new Date(rowB.cells[4].textContent.split('.').reverse().join('-'));
            return sortOrder === 'desc' ? dateB - dateA : dateA - dateB;
        });

        // Перезаписываем строки в таблице
        const tbody = document.querySelector('#sales-table tbody');
        rows.forEach(row => tbody.appendChild(row));
    });
});

