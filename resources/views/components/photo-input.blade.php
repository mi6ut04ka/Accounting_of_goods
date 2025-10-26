@props(['urls' => [], 'category' => false])

<div class="mb-3">
    @if(!empty($urls))
        <div class="mb-2 d-flex flex-wrap gap-3">
            @foreach($urls as $photo)
                <div id="photo-{{ $photo['id'] }}" class="position-relative" style="max-width: 180px; margin: 10px;">
                    <img src="{{ asset('https://s3.regru.cloud/aromosa/' . $photo['url']) }}"
                         alt="Фото"
                         class="img-thumbnail" style="max-height: 150px; object-fit: cover;">

                    @if($photo['is_primary'])
                        <span class="badge bg-primary position-absolute top-0 start-0">Основное</span>
                    @endif

                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                            onclick="deletePhoto('{{ $photo['id'] }}')">
                        &times;
                    </button>

                    @if(!$photo['is_primary'])
                        <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 start-0"
                                onclick="setPrimary({{ $photo['id'] }})">
                            Сделать основным
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <label for="photos" class="form-label">
        {{ $category ? 'Фото категории' : 'Фото продукта' }}
    </label>

    <input type="file" name="photos[]" id="photos" class="form-control" multiple onchange="previewPhotos()">

    @error('photos')
    <small class="text-danger">{{ $message }}</small>
    @enderror

    @error('photos.*')
    <small class="text-danger">{{ $message }}</small>
    @enderror

    <div id="preview-photos" class="mt-3 d-flex flex-wrap gap-3"></div>
</div>

<script>
    // Функция для отображения выбранных фото
    function previewPhotos() {
        const input = document.getElementById('photos');
        const previewContainer = document.getElementById('preview-photos');
        previewContainer.innerHTML = '';

        const files = input.files;
        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.classList.add('position-relative');
                    imageContainer.style.maxWidth = '180px';
                    imageContainer.style.margin = '10px';
                    imageContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Фото" class="img-thumbnail" style="max-height: 150px; object-fit: cover;">
                        <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 start-0" onclick="setPrimary(${index})">
                            Сделать основным
                        </button>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="deletePhoto(${index})">
                            &times;
                        </button>
                    `;
                    previewContainer.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function setPrimary(photoId) {
        fetch('/photos/set-primary', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ photo_id: photoId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Фото установлено как основное');
                    document.location.reload();
                } else {
                    alert('Ошибка при установке фото основным');
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка при установке фото основным');
            });
    }

    function deletePhoto(photoId) {
        if (confirm('Вы уверены, что хотите удалить это фото?')) {
            fetch(`/photos/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('photo-' + photoId).remove(); // Удаляем фото с экрана
                        alert('Фото удалено');
                    } else {
                        alert('Ошибка при удалении фото');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Ошибка при удалении фото');
                });
        }
    }
</script>
