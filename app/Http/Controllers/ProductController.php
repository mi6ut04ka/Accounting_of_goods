<?php

namespace App\Http\Controllers;

use App\Models\Aroma;
use App\Models\Category;
use App\Models\Product;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{

    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryId = request('category');


        if (!$categoryId) {
            $category = Category::where('is_final', 1)->where('type', 'product')->first();
            if ($category) {
                return redirect()->route('products.index', ['category' => $category->id]);
            } else {
                abort(404, 'Конечная категория с типом "product" не найдена');
            }
        }

        $category = Category::find($categoryId);

        if (!$category || $category->type != 'product') {
            abort(404, 'Категория не найдена или не является категорией типа "product"');
        }

        if ($category->is_set) {
            return redirect()->route('products.sets.index', ['category' => $category->id]);
        }

        $products = Product::where('category_id', $categoryId)->orderBy('created_at', 'desc')->paginate(11);

        return view('products.index', compact('category', 'products'));
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $products = Product::all()->filter(function ($product) use ($query) {
            return str_contains(mb_strtolower($product->name), $query) == true;
        })->take(10)
            ->values()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'in_stock' => $product->in_stock,
                ];
        });

        return response()->json($products);
    }

    public function updateStock(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'in_stock' => 'required|integer|min:0',
            'aromaId' => 'nullable|exists:aromas,id',
        ]);

        if(!$validatedData['aromaId']){
            $product->in_stock = $validatedData['in_stock'];
            $product->save();
        }else{
            $product->aromas()->updateExistingPivot($validatedData['aromaId'], [
                'in_stock' => $validatedData['in_stock'],
            ]);
        }

        return response()->json(!$validatedData['aromaId'] ? ['in_stock' => $product->in_stock, 'status' => 'Количество товара успешно обновлено'] : ['in_stock' => $product->aromas()->where('aroma_id', $validatedData['aromaId'])->first()->pivot->in_stock, 'status' => 'Количесто товара успешно обновлено']);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $category_id = $request->get('category');
        $aromas = Aroma::all();

        $category = Category::with('attributes', 'parent.attributes')->findOrFail($category_id);


        if ($category->type != 'product') {
            abort(404, 'Невозможно создать продукт в этой категории');
        }

        $attributes = collect();

        $currentCategory = $category;
        while ($currentCategory) {
            $attributes = $attributes->merge($currentCategory->attributes);
            $currentCategory = $currentCategory->parent;
        }

        if($category->is_set){
            $products = Product::whereHas('category', function ($query) {
                $query->where('is_set', false);
            })->get();
            return view('products.sets.create', compact('category', 'products'));
        }

        return view('products.create', [
            'aromas' => $aromas,
            'category' => $category,
            'attributes' => $attributes->unique('id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'aromas' => 'nullable|array|exists:aromas,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantities' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'in_stock' => 'required|integer|min:0',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'attributes' => 'array',
            'attributes.*' => 'nullable',
        ]);

        $category = Category::find($validated['category_id']);

        if ($category->type != 'product') {
            abort(404, 'Невозможно создать продукт в этой категории');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'cost' => $validated['cost'],
            'in_stock' => $validated['in_stock'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
        ]);
        if(!empty($validated['aromas']) && !empty($validated['in_stock'])) {
            foreach ($validated['aromas'] as $index => $aromaId) {
                $product->aromas()->attach($aromaId, ['in_stock' => $validated['in_stock'][$index] ?? 0]);
            }
        }

        if ($request->hasFile('photos')) {
            $this->handlePhotos($product, $request);
        }

        if (!empty($validated['attributes'])) {
            foreach ($validated['attributes'] as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $product->attributeValues()->create([
                    'attribute_id' => $attributeId,
                    'value' => $value,
                ]);
            }
        }

        return redirect()->route('products.index', ['category' => $validated['category_id']])->with('success', 'Продукт успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('photos', 'attributeValues.attribute')->findOrFail($id);

        $attributes = $product->attributeValues->map(function ($attributeValue) {
            return [
                'name' => $attributeValue->attribute->name,
                'value' => $attributeValue->value,
            ];
        });

        return view('products.show', compact('product', 'attributes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        $aromas = Aroma::all();

        $category = $product->category;

        if ($category->is_set) {
            return redirect()->route('products.sets.edit', ['category' => $category->id , 'set' => $product->id]);
        }

        $attributes = collect();

        $currentCategory = $category;
        while ($currentCategory) {
            $attributes = $attributes->merge($currentCategory->attributes);
            $currentCategory = $currentCategory->parent;
        }

        $attributes = $attributes->unique('id');

        return view('products.edit', compact('product', 'category', 'attributes', 'aromas'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'aromas' => 'nullable|array|exists:aromas,id',
            'quantities' => 'nullable|array',
            'in_stock' => 'required|integer|min:0',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'primary_photo' => 'nullable|exists:photos,id',
            'attributes' => 'array',
            'attributes.*' => 'nullable',
        ]);
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'cost' => $validated['cost'],
            'in_stock' => $validated['in_stock'],
        ]);

        $product->aromas()->detach();
        if (!empty($validated['aromas'])) {
            foreach ($validated['aromas'] as $index => $aromaId) {
                $inStock = $validated['quantities'][$index] ?? 0;
                $product->aromas()->attach($aromaId, ['in_stock' => $inStock]);
            }
        }
        if ($request->hasFile('photos')) {
            $this->handlePhotos($product, $request,);
        }

        if ($request->filled('primary_photo')) {
            $product->photos()->update(['is_primary' => false]);
            $product->photos()->where('id', $validated['primary_photo'])->update(['is_primary' => true]);
        }

        if (!empty($validated['attributes'])) {
            foreach ($validated['attributes'] as $attributeId => $value) {
                if ($value === null || $value === '') {
                    $product->attributeValues()->where('attribute_id', $attributeId)->delete();
                    continue;
                }

                $product->attributeValues()->updateOrCreate(
                    ['attribute_id' => $attributeId],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('products.index', ['category' => $validated['category_id']])->with('success', 'Продукт успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findorfail($id);

        $this->deletePhotos($product);

        $product->attributeValues()->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт успешно удален.');
    }

}
