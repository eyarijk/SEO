<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\User;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $tasks = $user->favorite_task()->where('is_show',true)->paginate(10);
        $category = Category::where('is_show', true)->get();
        return view('task.setting.favorite')
            ->withCategory($category)
            ->withTasks($tasks)
            ->withUser($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_favorite = User::find(auth()->id());

        if ($user_favorite->favorite_task()->find($request->id)){
            $user_favorite->favorite_task()->detach($request->id);
            return redirect()->back()->withToaststatus('success')->withToast('Задание удалено из избранных!');
        } else {
            $user_favorite->favorite_task()->attach($request->id);
            return redirect()->back()->withToaststatus('success')->withToast('Задание добавлено в избранное!');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
