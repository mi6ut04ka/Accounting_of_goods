<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Storage;

class PhotoController extends Controller
{
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        Storage::disk('s3')->delete($photo->url);

        $photo->delete();

        return response()->json(['success' => 'Фото удалено']);
    }

    public function setPrimary(Request $request)
    {
        $photo = Photo::findOrFail($request->input('photo_id'));

        Photo::where('product_id', $photo->product_id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $photo->is_primary = true;
        $photo->save();

        return response()->json(['success' => 'Фото установлено как основное']);
    }


}
