<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Context;
use Mail;
use App\Contact;

class ContactController extends Controller
{
    public function show()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('contact')->withUser($user)->withContexts($contexts);
    }

    public function send(Request $request)
    {
        $sender = $request;
        Mail::send('includes.forms.email', ['sender' => $sender], function($message) use ($sender) {
            $message->from($sender->email);
            $message->to('eyarij@gmail.com');
            $message->subject($sender->subject);
        });

        $contact = new Contact;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->save();



        return "Good!";
    }

}
