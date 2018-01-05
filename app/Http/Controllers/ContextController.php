<?php

namespace App\Http\Controllers;

use App\Context;
use Illuminate\Http\Request;
use App\User;
use App\Notification;

class ContextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $context = $user->context()->paginate(10);
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.context.context')->withUser($user)->withContexts($contexts)->withContext($context);
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
        return view('manage.context.create')->withUser($user)->withContexts($contexts);
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
            'description' => 'required|min:10|max:200',
            'url' => 'required|',
        ));

        $context = new Context;
        $context->description = $request->description;
        $context->user_id = auth()->id();
        $context->url = $request->url;
        $context->save();

        return redirect()->route('contexts.index')->withToaststatus('success')->withToast('Контекстная реклама добавлена!');
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
        $context = $user->context()->find($id);
        if (!isset($context))
            return redirect('/manage/contexts');
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('manage.context.edit')
            ->withUser($user)
            ->withContexts($contexts)
            ->withContext($context);
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
            'description' => 'required|min:10|max:200',
            'url' => 'required|',
        ));
        $context = User::find(auth()->id())->context()->find($id);
        if (!isset($context))
            return redirect('/manage/contexts');
        $context->description = $request->description;
        $context->user_id = auth()->id();
        $context->url = $request->url;
        $context->save();

        return redirect()->route('contexts.index')->withToaststatus('success')->withToast('Контекстная реклама отредактирована!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $context = Context::find($id);
        $context->user->balance += $context->available * 0.2;
        $context->user->save();
        $context->delete();
        return redirect()->back()->withToaststatus('success')->withToast('Контекстная реклама удалена!');
    }

    public function status(Request $request)
    {
        $context = Context::find($request->id);

        if ($context->is_show == false)
            if ($context->available > 0)
                $context->is_show = true;
            else
                return redirect()->back()->withToaststatus('info')->withToast('Пополните баланс!');
        else
            $context->is_show = false;

        $context->save();

        return redirect()->back()->withToaststatus('success')->withToast('Статус изменен!');
    }

    public function pay($id)
    {
        $context = Context::find($id);
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        if($context->user_id != auth()->id())
            return redirect('/manage/context/');

        return view('manage.context.pay')->withUser($user)->withContext($context)->withContexts($contexts);
    }
    public function buy(Request $request)
    {
        $this->validate($request, array(
            'count' => 'required|numeric',
            'id' => 'required|numeric',
        ));

        $user = User::find(auth()->id());
        $context = $user->context()->where('id',$request->id)->first();
        $request->count = floor($request->count);
        $price = $request->count * 0.2;

        if ($user->balance < $price)
            return redirect()->back()->withToaststatus('info')->withToast('Недостаточно средств на балансе!');

        $user->balance -= $price;
        $context->available += $request->count;

        $user->save();
        $context->save();

        $notification = new Notification;
        $notification->user_id = auth()->id();
        $notification->description = 'Пополнение контекстной рекламы: «'.$context->description.'»  на : '.intval($request->count).' клик(ов). :) .';
        $notification->status = 'is-primary';
        $notification->save();

        return redirect()->route('contexts.index')->withToaststatus('success')->withToast('Баланс пополнен!');

    }

    public function redirect(Request $request)
    {
        $context = Context::find($request->id);
        $context->available--;

        if ($context->available <= 0){
            $context->is_show = false;
            $notification = new Notification;
            $notification->user_id = $context->user_id;
            $notification->description = 'Баланс на 0! Пополните контекстную рекламу: «'.$context->description.'» :) .';
            $notification->status = 'is-warning';
            $notification->save();
            $context->available = 0;
        }
        $context->save();

        return $context->url;
    }
}
