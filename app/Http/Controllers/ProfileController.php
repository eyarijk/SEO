<?php

namespace App\Http\Controllers;

use App\Purse;
use Illuminate\Http\Request;
use App\User;
use App\Context;

class ProfileController extends Controller
{
    public function show(){
        $user = User::find(auth()->id());
        $contexts = Context::inRandomOrder()->where('is_show',true)->limit(5)->get();
        return view('profile.show')->withContexts($contexts)->withUser($user);
    }
    public function avatar(Request $request){

        $this->validate($request, array(
            'image' => 'image|required|mimes:jpeg,jpg,png,svg,bmp|max:5000',
        ));

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $destinationPath = public_path() . '/images/user/';
            $filename = time() . '.' . $file->getClientOriginalExtension() ?: 'png';
            $request->file('image')->move($destinationPath, $filename);
            $user = User::find(auth()->id());

            if ($user->avatar != null  and file_exists($destinationPath.$user->avatar)){
                unlink($destinationPath.$user->avatar);
            }

            $user->avatar = $filename;
            $user->save();
            return redirect('/profile');
        }
    }
    public function update(Request $request){
        $this->validate($request, array(
            'name' => 'required|max:60|min:3',
            'email' => 'required|max:60|min:5',
        ));
        $user = User::find(auth()->id());

        if ($request->name != $user->name){
            $user->name = $request->name;
        }

        if ($request->email != $user->email){
            $valid = User::where('email',$request->email)->first();

            if(isset($valid)){
                return redirect('/profile');
            }

            $user->email = $request->email;
        }

        if (isset($request->newpassword)){
            $this->validate($request, array(
                'newpassword' => 'required|max:60|min:6',
                'renewpassword' => 'required|max:60|min:6',
            ));

            if ($request->newpassword != $request->renewpassword){
                return redirect('/profile');
            }

            $user->password = bcrypt($request->newpassword);
        }

        if (isset($request->money)){
            if ($request->money == 'webmoney'){
                if (isset($request->webmoney)){
                    $this->validate($request, array(
                        'webmoney' => 'required|max:13|min:13',
                    ));
                    $valid_webmoney = strtoupper(substr($request->webmoney,0,1));

                    if ($valid_webmoney != 'R'){
                        return redirect('/profile');
                    }

                    $valid_webmoney_in_db = Purse::where('purse',$request->webmoney)
                        ->where('name','webmoney')
                        ->where('id','not',auth()->id())
                        ->first();
                    if (isset($valid_webmoney_in_db)){
                        return redirect('/profile');
                    }

                    $webmoney = $user->purse->where('name','webmoney')->first();
                    if (isset($webmoney)){
                        $new_webmoney = $webmoney;
                    } else {
                        $new_webmoney = new Purse;
                    }
                    $new_webmoney->name = 'webmoney';
                    $new_webmoney->purse = $request->webmoney;
                    $new_webmoney->user_id = auth()->id();
                    $new_webmoney->save();
                }
            } elseif ($request->money == 'yandex'){
                $this->validate($request, array(
                    'yandex' => 'required|max:14|min:13',
                ));

                $valid_yandex_in_db = Purse::where('purse',$request->yandex)
                    ->where('name','yandex')
                    ->where('id','not',auth()->id())
                    ->first();
                if (isset($valid_yandex_in_db)){
                    return redirect('/profile');
                }

                $yandex = $user->purse->where('name','yandex')->first();
                if (isset($yandex)){
                    $new_yandex = $yandex;
                } else {
                    $new_yandex = new Purse;
                }

                $new_yandex->name = 'yandex';
                $new_yandex->purse = $request->yandex;
                $new_yandex->user_id = auth()->id();
                $new_yandex->save();

            } elseif ($request->money == 'qiwi'){
                $this->validate($request, array(
                    'qiwi' => 'required|max:13|min:13',
                ));
                $valid_qiwi = substr($request->qiwi,0,1);
                if ($valid_qiwi != '+'){
                    return redirect('/profile');
                }

                $valid_qiwi_in_db = Purse::where('purse',$request->qiwi)
                    ->where('name','qiwi')
                    ->where('id','not',auth()->id())
                    ->first();
                if (isset($valid_qiwi_in_db)){
                    return redirect('/profile');
                }

                $qiwi = $user->purse->where('name','qiwi')->first();
                if (isset($qiwi)){
                    $new_qiwi = $qiwi;
                } else {
                    $new_qiwi = new Purse;
                }
                $new_qiwi->name = 'qiwi';
                $new_qiwi->purse = $request->qiwi;
                $new_qiwi->user_id = auth()->id();
                $new_qiwi->save();
            } else {
                return redirect('/profile');
            }
        }

        $user->save();
        return redirect('/profile');

    }
}
