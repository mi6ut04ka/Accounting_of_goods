<div class="mb-3">
    <label for="photo" class="form-label">Фото продукта</label>
    <input type="file" name="photo" id="photo" class="form-control">
    @error('photo')
    <small class="text-danger">{{ $message }}</small>
    @enderror
    @error('photo.*')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
