<?php

namespace App\Http\Controllers;

use App\Point_log;
use Illuminate\Http\Request;
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
            'name' => 'required|max:255|unique:users,name|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|min:6|confirmed|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
            'phone' => 'required|digits_between:10,12|numeric'
        ], [
            'name.regex' => __('shop.nameregex'),
            'name.unique' => __('shop.nameunique'),
            'email.email' => __('shop.emailvalidation'),
            'email.unique' => __('shop.emailunique'),
            'password.min' => __('shop.passwordmin'),
            'password.confirmed' => __('shop.passowrdcomfirmed'),
            'password.regex' => __('shop.passwordregex')
        ]);


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

        $user = User::where('email',$request->input('email'))->first();
        //if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) && $user->admin == 'N' && $user->status != 'Y') {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {

            $user->login_time = date("Y-m-d H:i:s");            ;
            $user->save();
            
            session()->put('lang', $request->language);

            return redirect()->intended('/');
        } else {
            Auth::logout();
            return view('welcome', ['error' => __('shop.accountpassworderror')]);
        }
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
        ], [
            'password.min' => __('shop.passwordmin'),
            'password.confirmed' => __('shop.passowrdcomfirmed'),
            'password.regex' => __('shop.passwordregex')
        ]);

        $user->password = bcrypt(($request->input('password')));
        $user->updated_at = date("Y-m-d H:i:s"); ;
        $user->save();

        return view('user.password', ['success' => 'success']);
    }

    public function point()
    {
        $pointLog = Point_log::where('log_user_id', '=', Auth::id())->orderBy('log_time', 'DESC')->get();
        $type = [
            '1' => '消費',
            '2' => '訂單結束取得',
            '3' => '訂單取消返回',
            '4' => '退貨返回',
            '5' => '退貨扣除禮金'
        ];
        return view('user.point', [
            'pointLog' => $pointLog,
            'type' => $type
            ]);
    }
}
