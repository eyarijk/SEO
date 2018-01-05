<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Post;
use App\Context;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $posts = Post::orderBy('created_at','desc')->paginate(5);
        return view('admin.posts.index')->withUser($user)->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        return view('admin.posts.create')->withUser($user);
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
            'name' => 'required|max:100|min:5',
            'description' => 'required|min:10|max:3000',
        ));
        $post = new Post;
        $post->title = $request->name;
        $post->slug = date('U').'-'.str_slug($request->name);
        $post->content = $request->description;
        $post->author_id = auth()->id();
        $post->save();

        return redirect()->route('posts.index')->withToaststatus('success')->withToast('Пост добавлен!');
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
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back()->withToaststatus('success')->withToast('Пост удален!');
    }
    public function status(Request $request)
    {
        $post = Post::findOrFail($request->id);

        if ($post->is_show == false)
            $post->is_show = true;
        else
            $post->is_show = false;

        $post->save();

        return redirect()->back()->withToaststatus('success')->withToast('Статус изменен!');
    }
    public function news()
    {
        $posts = Post::where('is_show',true)->orderBy('created_at','desc')->paginate(10);
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('news')->withUser($user)->withPosts($posts)->withContexts($contexts);
    }
    public function like(Request $request)
    {
        $this->validate($request, array(
            'id' => 'required|numeric',
        ));

        $like_post = User::find(auth()->id());

        if ($like_post->likepost()->find($request->id)){
            $like_post->likepost()->detach($request->id);
            $toast = 'Лайк убран!';
        } else {
            $like_post->likepost()->attach($request->id);
            $toast = 'Лайк добавлен!';
        }

        return redirect()->route('news')->withToaststatus('success')->withToast($toast);

    }
}
