<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\Role;
use Session;
use App\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $user = User::find(auth()->id());
        return view('admin.roles.index')->withRoles($roles)->withUser($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $permissions = Permission::all();
        $user = User::find(auth()->id());
      return view('admin.roles.create')->withPermissions($permissions)->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validateWith([
        'display_name' => 'required|max:255',
        'name' => 'required|max:100|alpha_dash|unique:roles',
        'description' => 'sometimes|max:255'
      ]);
      $role = new Role();
      $role->display_name = $request->display_name;
      $role->name = $request->name;
      $role->description = $request->description;
      $role->save();
      if ($request->permissions) {
        $role->syncPermissions(explode(',', $request->permissions));
      }
      return redirect()->route('roles.show', $role->id)->withToaststatus('success')->withToast('Добавлена роль!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::where('id',$id)->with('permissions')->first();
        $user = User::find(auth()->id());
        return view('admin.roles.show')->withRole($role)->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $role = Role::where('id',$id)->with('permissions')->first();
      $permissions = Permission::all();
        $user = User::find(auth()->id());
      return view('admin.roles.edit')->withRole($role)->withPermissions($permissions)->withUser($user);
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
          'display_name' => 'required|max:255',
          'description' => 'sometimes|max:255'
        ]);
        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        if($request->permissions)
          $role->syncPermissions(explode(',',$request->permissions));

        return redirect()->route('roles.show',$id)->withToaststatus('success')->withToast('Отредактирована роль!');

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
}
