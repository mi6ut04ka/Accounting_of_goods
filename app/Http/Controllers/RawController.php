<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Raw;
use App\Models\ProductAttribute;
use App\Traits\HandlesPhoto;
use Illuminate\Http\Request;

class RawController extends Controller
{
    use HandlesPhoto;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Raw::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $raws = $query->with('category')->get()->groupBy('category.name');
        return view('raws.index', compact('raws'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type', 'raw_material')->pluck('name', 'id');
        return view('raws.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|file',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|string|max:255'
        ]);

        $raw = Raw::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'link' => $validated['link'] ?? null,
            'category_id' => $validated['category_id'],
        ]);

        if ($request->hasFile('photo')) {
            $this->handlePhoto($raw, $request, 'raws');
        }

        if (!empty($validated['attributes'])) {
            $attributes = [];
            foreach ($validated['attributes'] as $attributeId => $value) {
                $attributes[] = [
                    'attribute_id' => $attributeId,
                    'value' => $value
                ];
            }
            $raw->attributeValues()->createMany($attributes);
        }

        return redirect()->route('raws.index')->with('success', 'Сырьё успешно добавлено.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $raw = Raw::with('attributes')->findOrFail($id);
        $categories = Category::where('type', 'raw_material')->pluck('name', 'id');
        return view('raws.edit', compact('raw', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'nullable|url',
            'photo' => 'nullable|file',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|string|max:255'
        ]);

        $raw = Raw::findOrFail($id);
        $raw->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'link' => $validated['link'] ?? null,
        ]);

        if ($request->hasFile('photo')) {
            $this->updatePhoto($raw, $request->file('photo'), 'raws');
        }

        // Удаляем старые значения атрибутов и добавляем новые
        $raw->attributeValues()->delete();
        if (!empty($validated['attributes'])) {
            $attributes = [];
            foreach ($validated['attributes'] as $attributeId => $value) {
                $attributes[] = [
                    'attribute_id' => $attributeId,
                    'value' => $value
                ];
            }
            $raw->attributeValues()->createMany($attributes);
        }

        return redirect()->route('raws.index')->with('success', 'Сырьё успешно обновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $raw = Raw::findOrFail($id);
        $raw->attributes()->delete();
        $this->deletePhoto($raw);
        $raw->delete();

        return redirect()->back()->with('success', 'Сырье успешно удалено.');
    }
}
