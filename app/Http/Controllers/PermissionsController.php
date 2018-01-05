<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use Session;
use App\User;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        $user = User::find(auth()->id());
        return view('admin.permissions.index')->withPermissions($permissions)->withUser($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        return view('admin.permissions.create')->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->permission_type == 'basic') {
          $this->validate($request,[
            'display_name' => 'required|max:255',
            'name' => 'required|max:255|alphadash|unique:permissions,name',
            'description' => 'sometimes|max:255'
          ]);
          $permission = new Permission();
          $permission->name = $request->name;
          $permission->display_name = $request->display_name;
          $permission->description = $request->description;
          $permission->save();

          return redirect()->route('permissions.index')->withToaststatus('success')->withToast('Права добавлены!');
        }elseif($request->permission_type == 'crud'){
          $this->validate($request,[
            'resource' => 'required|min:3|max:100|alpha'
          ]);

          $crud = explode(',',$request->crud_selected);
          if(count($crud) > 0){
            foreach ($crud as $value) {
              $slug = strtolower($value).'-'.strtolower($request->resource);
              $display_name = ucwords($value," ".$request->resource);
              $description = "Allows a user to ".strtoupper($value).' a '.ucwords($request->resource);

              $permission = new Permission();
              $permission->name = $slug;
              $permission->display_name = $display_name;
              $permission->description = $description;
              $permission->save();
            }
            return redirect()->route('permissions.index')->withToaststatus('success')->withToast('Права добавлены!');
          }
        }else{
          return redirect()->route('permissions.create')->withInput();
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
        $permission = Permission::findOrFail($id);
        $user = User::find(auth()->id());
        return view('admin.permissions.show')->withPermission($permission)->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $user = User::find(auth()->id());
        return view('admin.permissions.edit')->withPermission($permission)->withUser($user);
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
      $permission = Permission::findOrFail($id);
      $permission->display_name = $request->display_name;
      $permission->description = $request->descriptin;
      $permission->save();
      Session::flash('success','Update the '.$permission->display_name.' permission');
      return redirect()->route('permissions.show',$id)->withToaststatus('success')->withToast($permission->display_name.' отредактировано!');;
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
