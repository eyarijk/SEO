<?php

namespace App\Http\Controllers;

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
        return view('admin.dashboard')->withUser($user);
    }
}
