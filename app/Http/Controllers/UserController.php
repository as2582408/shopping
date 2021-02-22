<?php

namespace App\Http\Controllers;

use App\Point_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Auth;
use App\User;


class UserController extends Controller
{
    //

    public function getSignup()
    {
        return view('shop.signup');
    }

    public function postSignup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
            'email' => 'email|required',
            'password' => 'required|min:6|confirmed|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
            'phone' => 'required|digits_between:10,12|numeric'
        ], [
            'name.regex' => __('shop.nameregex'),
            'email.email' => __('shop.emailvalidation'),
            'password.min' => __('shop.passwordmin'),
            'password.confirmed' => __('shop.passowrdcomfirmed'),
            'password.regex' => __('shop.passwordregex')
        ]);

        $userCheck = User::where([
            ['email', '=', $request->input('email')],
            ['status', '!=', 'D']
            ])->first();

        if (isset($userCheck)) {
            return redirect()->back()->withErrors(__('shop.emailunique'));
        }

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'password' => bcrypt(($request->input('password'))),
            'status	' => 'Y',//預設啟用
            'admin' => 'N',//角色使用者
            'level' => 0,//預設等級0級
            'point' => 0,//預設金額0
            'accumulation_point' => 0,//累計金額為0
            'login_time' => date("Y-m-d H:i:s")
        ]);
        $user->save();


        Auth::login($user);

        return redirect()->intended('/');
    }

    public function getSignin()
    {
        return view('welcome');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required|min:6'
        ], [
            'email.email' => __('shop.emailvalidation'),
            'password.min' => __('shop.passwordmin')
        ]);

        $user = User::where([
            ['email', $request->input('email')],
            ['admin', 'N']])->first();

        if($user == NULL){
            return view('welcome', ['error' => __('shop.accountpassworderror')]);
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])  && $user->status == 'Y') {
            $user->login_time = date("Y-m-d H:i:s");
            $user->save();
            return redirect()->intended('/');
        } elseif (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])  && $user->status == 'N') {
            return redirect('/mycenter')->withErrors(__('shop.suspended'));
        } 

        return view('welcome', ['error' => __('shop.accountpassworderror')]);
    }

    public function getCenter()
    {
        $user = Auth::user();
        return view('user.center', ['user' => $user]);
    }

    public function getProfile()
    {
        $user = Auth::user();
        return view('user.profile', ['user' => $user]);
    }

    public function editProfile(Request $request)
    {        
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
            'email' => 'email|required|unique:users,email,'.$user->id,
            'phone' => 'required|numeric|regex:/^09\d{8}$/'
        ], [
            'name.regex' => __('shop.nameregex'),
            'email.email' => __('shop.emailvalidation'),
        ]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->updated_at = date("Y-m-d H:i:s"); ;

        $user->save();

        return redirect()->intended('/mycenter');
    }

    public function getPassword()
    {
        return view('user.password');
    }

    public function editPassword(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'password' => 'required|min:6|confirmed|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
            'oldpassword' => 'required|min:6|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
        ], [
            'password.min' => __('shop.passwordmin'),
            'password.confirmed' => __('shop.passowrdcomfirmed'),
            'password.regex' => __('shop.passwordregex')
        ]);
        if(!Auth::attempt(['email' => $user->email, 'password' => $request->input('oldpassword')]))
        {
            return view('user.password')->withErrors(__('shop.oldpassworderror'));
        }
        $user->password = bcrypt($request->input('password'));
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return view('user.password', ['success' => 'success']);
    }

    public function point()
    {
        $pointLog = Point_log::where('log_user_id', '=', Auth::id())->orderBy('log_id', 'DESC')->get();
        $type = [
            '1' => __('shop.shopping'),
            '2' => __('shop.order End'),
            '3' => __('shop.order cancel'),
            '4' => __('shop.return order'),
            '5' => __('shop.return gift'),
            '6' => __('shop.ChagePoint')
        ];
        return view('user.point', [
            'pointLog' => $pointLog,
            'type' => $type
            ]);
    }

    public function chageLang($lang)
    {   
        $minutes = 43200;
        return redirect()->back()->withCookie(cookie('lang', $lang, $minutes));
    }
}
