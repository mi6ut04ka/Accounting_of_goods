<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getImage($folder, $filename)
    {
        $path = 'public/' . $folder . '/' . $filename;
        if (!Storage::exists($path)) {
            abort(404);
        }
        return response()->file(Storage::path($path));
    }
}
