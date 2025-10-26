<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::all()->sortByDesc('created_at');

        return view('messages.index', compact('messages'));
    }

    public function destroy(string $id)
    {
        dd($id);
        Message::destroy($id);

        return redirect()->route('messages.index')->with('success', 'Сообщение успешно удалено');
    }
}
