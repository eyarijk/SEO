<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Contact;
use App\Context;
use App\Message;
use App\Surfing;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class ManageController extends Controller
{
    public function index()
    {
      return redirect()->route('manage.dashboard');
    }
    public function dashboard()
    {
        $user = User::find(auth()->id());
        $users = User::all()->count();
        $balance = User::all()->sum('balance');
        $categories = Category::all()->count();
        $contact = Contact::where('is_show',true)->get()->count();
        $tasks = Task::all()->count();
        $surfing = Surfing::all()->count();
        $message = Message::all()->count();
        $context = Context::all()->count();
        $banners = Banner::all()->count();
        $all = $context + $banners;
        return view('admin.dashboard')
            ->withUser($user)
            ->withUsers($users)
            ->withBalance($balance)
            ->withCategories($categories)
            ->withContact($contact)
            ->withTasks($tasks)
            ->withSurfing($surfing)
            ->withMessage($message)
            ->withAll($all);
    }
}
