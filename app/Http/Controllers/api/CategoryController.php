<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categorySlug = request('parent');

        if($categorySlug)
        {
            $category = Category::where('slug', $categorySlug)->first();

            return response()->json($category->children->map(function($category){
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'imageSrc' => $category->photo->url ?? null,
                    'link'=> $category->slug
                ];
            }));

        }
        $categories = Category::where('is_visible', 1)
            ->where('type', 'product')
            ->whereNull('parent_id')
            ->get();
        return response()->json($categories->map(function($category){
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'imageSrc' => $category->photo->url ?? null,
                'link'=> $category->slug
            ];
        }));
    }

    public function show($param)
    {
        $categoryQuery = is_numeric($param)
            ? Category::where('id', $param)
            : Category::where('slug', $param);


        $category = $categoryQuery->with('children', 'parent')->firstOrFail();
        $subcategories = $category->children->map(fn($subcategory) => [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'slug' => $subcategory->slug,
            'description' => $subcategory->description,
            'imageSrc' => $subcategory->photo->url ?? null
        ]);
        return response()->json([
            'category' => $category,
            'subcategories' => $subcategories
        ]);
    }

    public function getFilters($slug)
    {
        // Получаем категорию по slug
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }
        $filters = $this->getCategoryFilters($category);

        return response()->json([
            'category' => $category->name,
            'filters' => $filters
        ]);
    }

    private function getCategoryFilters($category)
    {
        $filters = [];
        while ($category) {
            $attributes = ProductAttribute::where('category_id', $category->id)->get();

            foreach ($attributes as $attribute) {
                $values = $attribute->values()->pluck('value')->unique()->toArray();

                $filters[] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'values' => $values
                ];
            }

            $category = $category->parent;
        }

        return collect($filters)->unique('id')->values();
    }
}
