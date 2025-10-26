<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $user = \Auth::user();

        $address = Address::where('user_id', $user->id)->first();

        return response()->json($address);
    }

    public function update(Request $request)
    {
        $valideted = $request->validate([
            'city' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'postal_code' => 'required'
        ]);
        $user = \Auth::user();

        $address = $user->address()->first();
        if($address)
        {
            $address->update($valideted);
        }
        else
        {
            $address = $user->address()->create($valideted);
        }

        return response()->json(['success' => 'Адрес успешно изменен']);
    }
}
