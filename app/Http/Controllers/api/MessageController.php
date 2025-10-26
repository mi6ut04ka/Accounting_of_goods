<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'email' => 'required|email',
            'name' => 'required',
        ]);

        Message::create($request->all());

        return response()->json(['success' => 'Сообщение успешно отправлено']);
    }
}
