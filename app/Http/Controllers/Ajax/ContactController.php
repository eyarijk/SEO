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
    public function admin()
    {
        $user = User::find(auth()->id());
        $contacts = Contact::where('is_show',true)->orderBy('id','asc')->paginate(10);
        return view('admin.contact.index')->withUser($user)->withContacts($contacts);
    }
    public function details($id)
    {
        $user = User::find(auth()->id());
        $contact = Contact::findOrFail($id);
        return view('admin.contact.details')->withUser($user)->withContact($contact);
    }
    public function answer(Request $request)
    {
        $this->validate($request, array(
            'answer' => 'required|min:10',
        ));
        $contact = Contact::findOrFail($request->id);
        $answer = $request->answer;
        Mail::send('includes.forms.contact', ['contact' => $contact,'answer' => $answer], function($message) use ($contact) {
            $message->from('eyarij@gmail.com');
            $message->to($contact->email);
            $message->subject('Заявка #'.$contact->id);
        });
        $contact->is_show = false;
        $contact->save();
        return redirect('/admin/contact');
    }
    public function delete(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        $contact->delete();
        return redirect('/admin/contact');
    }

}
