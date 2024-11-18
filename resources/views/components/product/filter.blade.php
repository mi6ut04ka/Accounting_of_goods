<form action="{{ route('products.index') }}" method="GET" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-3">
            <label for="category" class="form-label">Категория</label>
            <select name="category" id="category" class="form-select">
                <option value="">Все категории </option>
{{--                @foreach($categories as $category)--}}
{{--                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>--}}
{{--                        {{ $category->name }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
            </select>
        </div>
        <div class="col-md-3">
            <label for="min_price" class="form-label">С меньше цены</label>
            <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}" placeholder="Min price">
        </div>
        <div class="col-md-3">
            <label for="max_price" class="form-label">С большей цены</label>
            <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="Max price">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Отфильровать</button>
        </div>
    </div>
</form>

