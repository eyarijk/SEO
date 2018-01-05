<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        $user = User::find(auth()->id());
        return view('admin.category.index')->withUser($user)->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        return view('admin.category.create')->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:30|min:3',
        ));
        if(isset($request->is_show))
            $show = true;
        else
            $show = false;

        $category = New Category;
        $category->name=$request->name;
        $category->slug=str_slug($category->name);
        $category->is_show = $show;
        $category->save();


        return redirect()->back()->withToaststatus('success')->withToast('Категория добавлена!');

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
        $user = User::find(auth()->id());
        $category = Category::findOrFail($id);
        return view('admin.category.edit')->withUser($user)->withCategory($category);
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
        $this->validate($request, array(
            'name' => 'required|max:30|min:3',
        ));
        if(isset($request->is_show))
            $show = true;
        else
            $show = false;

        $category = Category::findOrFail($id);
        $category->name=$request->name;
        $category->slug=str_slug($category->name);
        $category->is_show = $show;
        $category->save();

        return redirect()->route('category.index')->withToaststatus('success')->withToast('Категория сохранена!');

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
