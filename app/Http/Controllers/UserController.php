<?php

namespace App\Http\Controllers;

use App\Referral;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use DB;
use Session;
use Hash;
use Input;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(20);
        return view('admin.users.index')->withUsers($users)->withPaginate(true);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        return view('admin.users.create')->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
          'name' => 'required|max:255',
          'email' => 'required|email|unique:users'
        ]);

        if ($request->filled('password') && !empty($request->password)) {
          $password = trim($request->password);
        }else{
          $length = 10;
          $keyspace = '123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
          $str = '';
          $max = mb_strlen($keyspace, '8bit') - 1;
          for ($i=0; $i < $length; $i++) {
            $str .= $keyspace[random_int(0,$max)];
          }
          $password = $str;
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        if($user->save()){
          return redirect()->route('users.show',$user->id)->withToaststatus('success')->withToast('Пользователь создан!');
        }else{
          return redirect()->route('users.create')->withToaststatus('error')->withToast('Ошибка!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::where('id',$id)->with('roles')->first();
        $user = User::find(auth()->id());
        return view('admin.users.show')->withUser($user)->withUsers($users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $roles = Role::all();
      $users = User::where('id',$id)->with('roles')->first();
      $user = User::find(auth()->id());
      return view('admin.users.edit')->withUser($user)->withRoles($roles)->withUsers($users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request,[
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,'.$id,
        'percent_referrals' => 'numeric',
      ]);
      $user = User::findOrFail($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->percent_referrals = $request->percent_referrals;
      if($request->password_options == 'auto'){
        $length = 10;
        $keyspace = '123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i=0; $i < $length; $i++) {
          $str .= $keyspace[random_int(0,$max)];
        }
          $user->password = Hash::make($str);
      }elseif($request->password_options == 'manual'){
        $user->password = Hash::make($request->password);
      }
      $user->save();
      $user->syncRoles(explode(',',$request->roles));

      return redirect()->route('users.show',$id)->withToaststatus('success')->withToast('Пользователь отредактирован!');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function apiCheckUnique(Request $request)
    {
        return json_encode(User::where('email',$request->email)->exists());
    }
    public function search(Request $request)
    {
        if ($request->select == 'ID'){
            $users = User::where('id',$request->value)->get();
        }elseif ($request->select == 'email'){
            $users = User::where('email','LIKE','%'.$request->value.'%')->get();
        }elseif ($request->select == 'name'){
            $users = User::where('name','LIKE','%'.$request->value.'%')->get();
        }

        return view('admin.users.index')->withUsers($users)->withPaginate(false);
    }
}
