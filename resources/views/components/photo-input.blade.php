@props(['url'=>''])
<div class="mb-3">
    @if($url)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $url) }}" alt="Текущее фото" class="img-thumbnail" style="max-height: 150px;">
        </div>
    @endif
    <label for="photo" class="form-label">Фото продукта</label>
    <input type="file" name="photo" id="photo" class="form-control">
    @error('photo')
    <small class="text-danger">{{ $message }}</small>
    @enderror
    @error('photo.*')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
