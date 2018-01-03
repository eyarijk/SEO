<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Context;
use App\Banner;
use App\Notification;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.banner.create')->withUser($user)->withContexts($contexts);
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
            'image' => 'image|required|mimes:jpeg,jpg,png,gif|max:200',
            'url' => 'required|',
            'name' => 'required|min:3|max:100'
        ));

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $destinationPath = public_path() . '/images/banner/';
            $filename = time() . '.' . $file->getClientOriginalExtension() ?: 'png';
            $request->file('image')->move($destinationPath, $filename);

            $banner = new Banner;
            $banner->user_id = auth()->id();
            $banner->url = $request->url;
            $banner->image = $filename;
            $banner->name = $request->name;
            $banner->save();
            return redirect('/manage/banner');
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
        $user = User::find(auth()->id());
        $banner = $user->banner()->find($id);

        if (!isset($banner))
            return redirect('/manage/banner');

        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.banner.edit')->withUser($user)->withContexts($contexts)->withBanner($banner);
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
            'image' => 'image|required|mimes:jpeg,jpg,png,gif|max:200',
            'url' => 'required|',
            'name' => 'required|min:3|max:100'
        ));
        $banner = User::find(auth()->id())->banner()->find($id);
        if (!isset($banner))
            return redirect('/manage/banner');
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $destinationPath = public_path() . '/images/banner/';
            $filename = time() . '.' . $file->getClientOriginalExtension() ?: 'png';
            $request->file('image')->move($destinationPath, $filename);
            unlink($destinationPath.$banner->image);
            $banner->url = $request->url;
            $banner->image = $filename;
            $banner->name = $request->name;
            $banner->save();
            return redirect('/manage/banner');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find(auth()->id());
        $banner = $user->banner()->find($id);
        if (!isset($banner))
            return redirect('/manage/banner');
        $user->balance += $banner->available * 0.2;
        $user->save();
        $banner->delete();
        return redirect('/manage/banner');
    }
    public function manage(){
        $user = User::find(auth()->id());
        $banners = $user->banner()->paginate(10);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.banner.index')->withUser($user)->withBanners($banners)->withContexts($contexts);
    }
    public function status(Request $request)
    {
        $banner = User::find(auth()->id())->banner()->find($request->id);
        if (!isset($banner))
            return redirect('/manage/banner');

        if ($banner->is_show == false)
            if ($banner->available > 0)
                $banner->is_show = true;
            else
                return redirect()->back()->withErrors('Пополните баланс');
        else
            $banner->is_show = false;

        $banner->save();

        return redirect()->back();
    }
    public function pay($id)
    {
        $user = User::find(auth()->id());
        $banner = $user->banner()->find($id);
        if (!isset($banner)){
            return redirect('/manage/banner');
        }
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.banner.pay')->withUser($user)->withBanner($banner)->withContexts($contexts);
    }
    public function buy(Request $request)
    {
        $this->validate($request, array(
            'count' => 'required|numeric',
            'id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $banner = $user->banner()->where('id',$request->id)->first();
        if (!isset($banner))
            return redirect('/manage/banner');
        $request->count = floor($request->count);
        $price = $request->count * 0.2;

        if ($user->balance < $price)
            return redirect()->back();

        $user->balance -= $price;
        $banner->available += $request->count;

        $user->save();
        $banner->save();

        $notification = new Notification;
        $notification->user_id = auth()->id();
        $notification->description = 'Пополнение баннера: «'.$banner->name.'»  на : '.intval($request->count).' клик(ов). :) .';
        $notification->status = 'is-primary';
        $notification->save();

        return redirect('/manage/banner');

    }
    public function redirectbanner($id){
        $banner = Banner::where('id',$id)->where('is_show',true)->first();
        if (!isset($banner))
            return redirect('/surfing');
        $banner->available--;
        if ($banner->available < 1)
            $banner->is_show = false;
        $banner->save();
        return redirect($banner->url);
    }
}
