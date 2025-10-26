<?php

namespace App\Http\Controllers;

use App\Models\Aroma;
use Illuminate\Http\Request;

class AromaController extends Controller
{
    public function index()
    {
        $aromas = Aroma::all();
        return view('aromas.index', compact('aromas'));
    }

    public function create()
    {
        return view('aromas.create');
    }

    public function store(Request $request){

        Aroma::create($request->all());
        return redirect()->route('aromas.index')->with('success', 'Аромат создан');;
    }

    public function edit($id)
    {
        $aroma = Aroma::find($id);

        return view('aromas.edit', compact('aroma'));
    }

    public function update(Request $request, $id) {
        $aroma = Aroma::find($id);

        $aroma->update($request->all());
        return redirect()->route('aromas.index')->with('success', 'Аромат обновлен');

    }

    public function destroy($id){
        Aroma::destroy($id);
        return redirect()->route('aromas.index')->with('success', 'Аромат удален');;
    }
}
