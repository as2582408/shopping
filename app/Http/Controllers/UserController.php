<?php

namespace App\Http\Controllers;

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

        return redirect()->intended('/poi');
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
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user->login_time = date("Y-m-d H:i:s");            ;
            $user->save();
            return redirect()->intended('/poi');
        } else {
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

        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
            'email' => 'email|required|unique:users,email',
            'phone' => 'required|numeric|regex:/^09\d{8}$/',
        ], [
            'name.regex' => __('shop.nameregex'),
            'email.email' => __('shop.emailvalidation'),
        ]);
        $user = Auth::user();
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
        $this->validate($request, [
            'password' => 'required|min:6|confirmed|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
        ], [
            'password.min' => __('shop.passwordmin'),
            'password.confirmed' => __('shop.passowrdcomfirmed'),
            'password.regex' => __('shop.passwordregex')
        ]);

        $user = Auth::user();
        $user->password = bcrypt(($request->input('password')));
        $user->updated_at = date("Y-m-d H:i:s"); ;
        $user->save();

        return view('user.password', ['success' => 'success']);
    }
}
