<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductAttribute;
use App\Traits\HandlesPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use HandlesPhoto;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children', 'photo')
            ->whereNull('parent_id')
            ->get();

        $categories->load('attributes');
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id = null)
    {
        $parent = $id ? Category::find($id) : null;

        if ($id && !$parent) {
            abort(404, 'Категория не найдена.');
        }

        return view('categories.create', compact('parent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'is_final' => 'nullable',
                'is_set' => 'nullable',
                'is_visible' => 'nullable',
                'type' => 'required|in:raw_material,product',
                'description' => 'nullable|string',
                'photo' => 'nullable|file',
            ]);

            $validated['is_final'] = $request->has('is_final') ? 1 : 0;
            $validated['is_set'] = $request->has('is_set') ? 1 : 0;
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;

            $category = Category::create($validated);

            if ($request->hasFile('photo')) {
                $this->handlePhoto($category, $request, 'categories');
            }

            return redirect()->route('categories.index')->with('success', 'Категория создана.');

        } catch (ValidationException $e) {
            Log::error('Ошибка валидации: ', $e->errors());

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('children')->find($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        $category->load('attributes');

        $options = [
            'string' => 'Строка',
            'int' => 'Целое число',
            'select' => 'Выбор',
        ];

        return view('categories.edit', compact('category', 'options'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_final' => 'nullable',
            'is_set' => 'nullable',
            'is_visible' => 'nullable',
            'type' => 'required|in:raw_material,product',
            'description' => 'nullable|string',
            'photo' =>  'nullable|file|image|mimes:jpeg,png,jpg,gif,svg',
            'attributes.*.name' => 'required|string|max:255',
            'attributes.*.type' => 'required|string|in:string,int,select',
            'attributes.*.options' => 'nullable|string',
        ]);


        $validatedData['is_final'] = $request->has('is_final');
        $validatedData['is_set'] = $request->has('is_set') ? 1 : 0;
        $validatedData['is_visible'] = $request->has('is_visible') ? 1 : 0;

        $category->update([
            'name' => $validatedData['name'],
            'is_final' => $validatedData['is_final'],
            'is_set' => $validatedData['is_set'],
            'is_visible' => $validatedData['is_visible'],
            'type' => $validatedData['type'],
            'description' => $validatedData['description'],
        ]);

        if ($request->hasFile('photo')) {
            $this->updatePhoto($category, $request->file('photo'), 'categories');
        }

        $existingAttributes = $category->attributes->pluck('id')->toArray();
        $newAttributes = [];

        if (isset($validatedData['attributes'])) {
            foreach ($validatedData['attributes'] as $attributeData) {
                $attributeId = $attributeData['id'] ?? null;

                if ($attributeId && in_array($attributeId, $existingAttributes)) {
                    $category->attributes()->where('id', $attributeId)->update([
                        'name' => $attributeData['name'],
                        'data_type' => $attributeData['type'],
                        'options' => $attributeData['type'] === 'select'
                            ? explode(',', $attributeData['options'])
                            : null,
                    ]);
                } else {
                    $newAttributes[] = new ProductAttribute([
                        'name' => $attributeData['name'],
                        'data_type' => $attributeData['type'],
                        'options' => $attributeData['type'] === 'select'
                            ? explode(',', $attributeData['options'])
                            : null,
                    ]);
                }
            }
        }
        $category->attributes()->whereNotIn('id', array_column($validatedData['attributes'] ?? [], 'id'))->delete();

        if (!empty($newAttributes)) {
            $category->attributes()->saveMany($newAttributes);
        }

        return redirect()->route('categories.index')->with('success', 'Категория успешно обновлена!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::destroy($id);
        return redirect('/categories');
    }

    public function getAttributes(Category $category)
    {
        return response()->json($category->attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'data_type' => $attr->data_type,
                'options' => $attr->data_type === 'select'
                    ? (is_array($attr->options) ? $attr->options : json_decode($attr->options, true) ?? [])
                    : []
            ];
        }));
    }
}
