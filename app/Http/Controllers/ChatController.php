<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use App\Context;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $messages = Chat::orderBy('id','desc')->limit(10)->get();
        foreach ($messages as $val){
            $message[] = $val;
        }
        $message = array_reverse($message);

        return view('chat.show')->withUser($user)->withContexts($contexts)->withMessages($message);
    }
    public function send(Request $request)
    {
        $chat_message = new Chat;
        $chat_message->user_id = auth()->id();
        $chat_message->message = htmlspecialchars($request->message);
        $chat_message->save();
        return 'Message send';
    }
}
