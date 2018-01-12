<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Checkedsurfing;
use App\Surfing;
use App\User;
use App\Context;
use Illuminate\Http\Request;
use App\Notification;

class SurfingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        $notIn = $user->checkedsurfing()->get();
        if (count($notIn) > 0 )
            foreach ($notIn as $val)
                $not_show[] = $val->surfing_id;
        else
            $not_show[] = 0;

        $surfing = Surfing::where('is_show', true)->whereNotIn('id', $not_show)->paginate(10);
        return view('surfing.index')
            ->withUser($user)
            ->withSurfing($surfing)
            ->withTitle('Серфинг')
            ->withContexts($contexts);
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
        return view('surfing.create')->withUser($user)->withContexts($contexts);
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
            'time' => 'required|numeric',
            'window' => 'required|numeric',
            'url' => 'required|min:5',
        ));
        $salary = 0.035;

        if ($request->time == 20)
            $salary += 0;
        elseif($request->time == 30)
            $salary += 0.002;
        elseif($request->time == 40)
            $salary += 0.004;
        elseif($request->time == 50)
            $salary += 0.006;
        elseif($request->time == 60)
            $salary += 0.008;
        else
            return redirect()->back();

        if ($request->window == true)
            $salary += 0.008;

        $surfing = new Surfing;
        $surfing->user_id = auth()->id();
        $surfing->name = $request->name;
        $surfing->slug = str_slug($request->name).'-'.date('U');
        $surfing->salary = $salary * 0.8;
        $surfing->url = $request->url;
        $surfing->time = $request->time;
        $surfing->window = $request->window;
        $surfing->save();

        return redirect()->route('surfingmanage')->withToaststatus('success')->withToast('Серфинг создан!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $user = User::find(auth()->id());
        $surfing = Surfing::where('slug',$slug)->where('is_show',true)->first();
        if (!isset($surfing)){
            return redirect('/surfing');
        }
        $valid = $user->checkedsurfing()->where('surfing_id',$surfing->id)->first();
        if (isset($valid)){
            return redirect('/surfing');
        }
        $file = file_get_contents($surfing->url);
        $banner = Banner::inRandomOrder()->where('is_show',true)->limit(1)->first();
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('surfing.show')
            ->withUser($user)
            ->withContexts($contexts)
            ->withSurfing($surfing)
            ->withBanner($banner)
            ->withFile($file);
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
        $surfing = $user->surfing()->find($id);
        if (!isset($surfing))
            return redirect('/manage/surfing');
        if ($surfing->time == 20)
            $time = 0;
        elseif($surfing->time == 30)
            $time = 0.002;
        elseif($surfing->time == 40)
            $time = 0.004;
        elseif($surfing->time == 50)
            $time = 0.006;
        elseif($surfing->time == 60)
            $time = 0.008;

        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.surfing.edit')
            ->withUser($user)
            ->withContexts($contexts)
            ->withSurfing($surfing)
            ->withTime($time);
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
            'name' => 'required|max:100|min:5',
            'time' => 'required|numeric',
            'window' => 'required|numeric',
            'url' => 'required|min:5',
        ));
        $salary = 0.035;

        if ($request->time == 20)
            $salary += 0;
        elseif($request->time == 30)
            $salary += 0.002;
        elseif($request->time == 40)
            $salary += 0.004;
        elseif($request->time == 50)
            $salary += 0.006;
        elseif($request->time == 60)
            $salary += 0.008;
        else
            return redirect()->back()->withToaststatus('error')->withToast('Ошибка!');

        if ($request->window == true)
            $salary += 0.008;
        $salary *= 0.8;
        $salary = round($salary, 3);

        $user = User::find(auth()->id());
        $surfing = $user->surfing()->find($id);

        if (!isset($surfing))
            return redirect()->route('surfingmanage')->withToaststatus('error')->withToast('Серфинг не найден!');

        if ($surfing->name  != $request->name){
            $surfing->name = $request->name;
            $surfing->slug = str_slug($request->name).'-'.date('U');
        }

        if ($surfing->salary != $salary){
            $surfing->user->balance += $surfing->available * $surfing->salary / 0.8;
            $surfing->available = 0;
            $surfing->is_show = false;
        }

        $surfing->salary = $salary;
        $surfing->url = $request->url;
        $surfing->time = $request->time;
        $surfing->window = $request->window;
        $surfing->user->save();
        $surfing->save();
        return redirect()->route('surfingmanage')->withToaststatus('success')->withToast('Сохранено!');
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
        $surfing = $user->surfing()->find($id);
        if(!isset($surfing))
            return redirect()->route('surfingmanage')->withToaststatus('error')->withToast('Серфинг не найден!');
        $user->balance += $surfing->available * $surfing->salary / 0.8;
        $surfing->delete();
        $user->save();
        return redirect()->route('surfingmanage')->withToaststatus('success')->withToast('Удалено!');
    }
    public function valid(Request $request){
        $this->validate($request, array(
            'id' => 'required|numeric',
            'first' => 'required|numeric',
            'second' => 'required|numeric',
        ));
        $first = $request->first;
        $second = $request->second;
        $total = $first + $second;
        $result['status'] = '';
        $checked = new Checkedsurfing;
        $checked->user_id = auth()->id();
        $checked->surfing_id = $request->id;
        if ($total == $request->answer){
            $surfing = Surfing::find($request->id);
            $user = User::find(auth()->id());
            $user->balance += $surfing->salary;
            $surfing->available--;
            $result['status'] = 'success';
            if ($surfing->window)
                $result['window'] = true;
            if ($surfing->available < 1) {
                $surfing->is_show = false;
                $notification = new Notification;
                $notification->user_id = $surfing->user_id;
                $notification->description = 'Закончились деньги на серфинге: <a href="/manage/surfing">Пополнить: «'.$surfing->name.'»</a> .';
                $notification->status = 'is-info';
                $notification->save();
            }
            $surfing->save();
            $user->save();
        } else {
           $result['status'] = 'danger';
        }
        $checked->status = $result['status'];
        $checked->save();
        return json_encode($result);
    }
    public function manage(){
        $user = User::find(auth()->id());
        $surfing = $user->surfing()->paginate(10);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.surfing.index')->withUser($user)->withSurfing($surfing)->withContexts($contexts);
    }
    public function status(Request $request)
    {
        $surfing = User::find(auth()->id())->surfing()->find($request->id);

        if ($surfing->is_show == false)
            if ($surfing->available > 0)
                $surfing->is_show = true;
            else
                return redirect()->back()->withToaststatus('info')->withToast('Пополните баланс!');
        else
            $surfing->is_show = false;

        $surfing->save();

        return redirect()->back()->withToaststatus('success')->withToast('Статус изменен!');
    }
    public function pay($id)
    {
        $user = User::find(auth()->id());
        $surfing = $user->surfing()->find($id);
        if(!isset($surfing))
            return redirect()->route('surfingmanage')->withToaststatus('error')->withToast('Серфинг не найден!');
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();

        return view('manage.surfing.pay')->withUser($user)->withSurfing($surfing)->withContexts($contexts);
    }
    public function buy(Request $request)
    {
        $this->validate($request, array(
            'count' => 'required|numeric',
            'id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $surfing = $user->surfing()->find($request->id);
        if(!isset($surfing))
            return redirect('/manage/surfing/');
        $request->count = floor($request->count);
        $price = $request->count * $surfing->salary / 0.8;

        if ($user->balance < $price)
            return redirect()->back()->withToaststatus('info')->withToast('Недостаточно средств на балансе!');

        $user->balance -= $price;
        $surfing->available += $request->count;

        $user->save();
        $surfing->save();

        $notification = new Notification;
        $notification->user_id = auth()->id();
        $notification->description = 'Пополнен серфинг: <a href="/manage/surfing/">'.$surfing->name.'</a> на : '.intval($request->count).' шт. :) .';
        $notification->status = 'is-primary';
        $notification->save();

        return redirect()->route('surfingmanage')->withToaststatus('success')->withToast('Пополнено!');
    }

}
