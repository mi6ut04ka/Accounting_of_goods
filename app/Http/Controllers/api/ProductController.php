<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index()
    {
        $user = auth('sanctum')->user();
        $priceFrom = request()->get('priceFrom');
        $priceTo = request()->get('priceTo');
        $inStock = request()->get('inStock');
        $categorySlug = request()->get('category');
        $queryFilters = request()->except(['priceFrom', 'priceTo', 'inStock', 'category', 'page']);

        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $categoryIds = $this->getCategoryHierarchy($category);
        if (empty($categoryIds)) {
            return response()->json(['error' => 'Invalid category'], 400);
        }
        $attributeMap = ProductAttribute::whereIn('category_id', $categoryIds)
            ->get()
            ->pluck('id', 'name')
            ->toArray();
        Log::error($attributeMap);
        $filters = [];
        foreach ($queryFilters as $name => $value) {
            $name = str_replace('_', ' ', $name);
            if (isset($attributeMap[$name]) && $value) {
                Log::error('uasd');
                $filters[$attributeMap[$name]] = is_array($value) ? $value : [$value];
            }
        }

        $products = Product::with(['category', 'attributeValues.attribute', 'photos'])
            ->whereIn('category_id', $categoryIds)
            ->when($priceFrom !== null, fn($query) => $query->where('price', '>=', $priceFrom))
            ->when($priceTo !== null, fn($query) => $query->where('price', '<=', $priceTo))
            ->when($inStock , fn($query) => $query->where('in_stock','>', 0))
            ->when(!empty($filters), function ($query) use ($filters) {
                foreach ($filters as $attributeId => $values) {
                    $query->whereHas('attributeValues', function ($subQuery) use ($attributeId, $values) {
                        $subQuery->where('attribute_id', $attributeId)->whereIn('value', $values);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $products->map(fn($product) => [
            'category' => $product->category->name,
            'categorySlug' => $product->category->slug,
            'aroma' => $product->aroma,
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'photos' => $product->photos,
            'in_stock' => $product->in_stock,
            'favorite' => $user?->favorites()->where('product_id', $product->id)->exists(),
        ]);

        $availableFilters = ProductAttribute::whereIn('category_id', $categoryIds)
            ->with('values')
            ->get()
            ->map(fn($attribute) => [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'values' => $attribute->values
                    ->pluck('value')
                    ->map(fn($v) => strtolower(trim($v)))
                    ->unique()
                    ->values(),
            ]);

        return response()->json([
            'category' => $category->name,
            'filters' => $availableFilters,
            'products' => [
                'data' => $modifiedData,
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
        ]);
    }

    private function getCategoryHierarchy($category)
    {
        $categoryIds = [$category->id];

        while ($category->parent_id) {
            $category = Category::find($category->parent_id);
            if ($category) {
                $categoryIds[] = $category->id;
            }
        }

        return $categoryIds;
    }
    public function bestsellers()
    {
        $user = \Auth::guard('sanctum')->user();

        $products = Product::withSum('sales', 'quantity')
            ->whereHas('category', function ($query) {
                $query->where('is_visible', 1);
            })
            ->orderByDesc('sales_sum_quantity')
            ->take(4)
            ->get()
            ->map(function ($product) use ($user) {

                $isFavorite = $user && $user->favorites()->where('product_id', $product->id)->exists();

                return [
                    'id' => $product->id,
                    'categorySlug' => $product->category->slug,
                    'name' => $product->name,
                    'price' => $product->price,
                    'photos' => $product->photos,
                    'description' => $product->description,
                    'in_stock' => $product->in_stock,
                    'favorite' => $isFavorite,
                ];
            });

        return response()->json($products);
    }

    public function new()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('is_visible', 1);
        })->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($product) {
            return [
                'id' => $product->id,
                'categorySlug' => $product->category->slug,
                'name' => $product->name,
                'price' => $product->price,
                'in_stock' => $product->in_stock,
                'description' => $product->description,
                'photos' => $product->photos
            ];
        });

        return response()->json($products);
    }

    public function show($id)
    {
        $user = request()->user();
        $product = Product::with('attributeValues')->find($id);
        $category = $product->category;
        $favorite = null;
        if ($user) {
            $favorite = $user->favorites()->where('product_id', $product->id)->exists();
        }

        $attributes = collect();

        $currentCategory = $category;
        while ($currentCategory) {
            $attributes = $attributes->merge($currentCategory->attributes);
            $currentCategory = $currentCategory->parent;
        }

        $attributes = $attributes->unique('id');


        $in_stock = !empty($product->aromas()) ? $product->aromas()->sum('in_stock') : $product->in_stock;
        return response()->json([
            'favorite' => $favorite,
            'categorySlug' => $product->category->slug,
            'id' => $product->id,
            'name' => $product->name,
            'aroma' => $product->aroma,
            'price' => $product->price,
            'in_stock' => $in_stock,
            'photos' => $product->photos,
            'aromas' => $product->aromas,
            'description' => $product->description,
            'attributes' => $attributes->map(fn($attribute) => [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'value' => $product->attributeValues->where('attribute_id', $attribute->id)->first()->value ?? ''
            ]),
        ]);
    }

    public function search($name){
        $products = Product::all()->filter(function ($product) use ($name) {
            return str_contains(mb_strtolower($product->name), $name) == true;
        })->take(10)
            ->values()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'categorySlug' => $product->category->slug,
                    'name' => $product->name,
                    'price' => $product->price,
                ];
            });

        return response()->json($products);
    }
}
