<?php

namespace App\Http\Controllers;

use App\Models\Raw;
use App\Traits\HandlesPhoto;
use App\Traits\HandlesRawPhotos;
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

        $raws = $query->with(['attributes', 'photo'])->orderBy('created_at', 'desc')->get();
        return view('raw.index', compact('raws'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('raw.create');
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
            'keys' => 'nullable|array',
            'values' => 'nullable|array',
            'keys.*' => 'required_with:values.*|string|max:255',
            'values.*' => 'required_with:keys.*|string|max:255',
            'photo' => 'nullable|file',
        ]);

        $raw = Raw::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'link' => $validated['link'],
        ]);

        if($request->hasFile('photo')){
            $this->handlePhoto($raw, $request, 'raws');
        }

        $attributes = [];

        if (!empty($validated['keys']) && !empty($validated['values'])) {
            foreach ($validated['keys'] as $index => $key) {
                if (!empty($key) && isset($validated['values'][$index])) {
                    $attributes[] = [
                        'key' => $key,
                        'value' => $validated['values'][$index],
                    ];
                }
            }
        }
        if (!empty($attributes)) {
            $raw->attributes()->createMany($attributes);
        }

        return redirect()->route('raws.index')->with('success', 'Сырьё успешно добавлено.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Raw $raw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $raw = Raw::with('attributes')->findOrFail($id);
        return view('raw.edit', compact('raw'));
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
            'keys' => 'nullable|array',
            'values' => 'nullable|array',
            'keys.*' => 'required_with:values.*|string|max:255',
            'values.*' => 'required_with:keys.*|string|max:255',
            'photo' => 'nullable|file',
        ]);

        $raw = Raw::findOrFail($id);
        $raw->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'link' => $validated['link'],
        ]);

        if($request->hasFile('photo')){
            $this->updatePhoto($raw, $request->file('photo'), 'raws');
        }

        $raw->attributes()->delete();
        $attributes = [];
        if (!empty($validated['keys']) && !empty($validated['values'])) {
            foreach ($validated['keys'] as $index => $key) {
                if (!empty($key) && isset($validated['values'][$index])) {
                    $attributes[] = [
                        'key' => $key,
                        'value' => $validated['values'][$index],
                    ];
                }
            }
        }
        if (!empty($attributes)) {
            $raw->attributes()->createMany($attributes);
        }

        return redirect()->route('raws.index')->with('success', 'Сырьё успешно обновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $raw = Raw::findorfail($id);
        $raw->attributes()->delete();
        $this->deletePhoto($raw);
        $raw->delete();

        return redirect()->back()->with('success', 'Сырье успешно удалено');
    }
}
