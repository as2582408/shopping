<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    //登出
    public function signOut()
    {
        Auth::logout();
        return redirect()->intended('/');
    }

    public function getSignin()
    {
        return view('admin.login');
    }
    //
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
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) && $user->admin == 'Y') {
            $user->login_time = date("Y-m-d H:i:s");
            $user->save();

            session()->put('lang', $request->language);
            
            return redirect('admin/center');
        } else {
            return view('admin.login', ['error' => __('shop.accountpassworderror')]);
        }
    }
    //後台首頁
    public function adminCenter()
    {
        $user = Auth::user();

        return view('admin.adminCenter');
    }

    //會員主頁面
    public function account()
    {
        $user = User::all();

        return view('admin.account', ['users_data' => $user]);
    }
    //編輯會員資料頁面
    public function editAccountPage($id)
    {
        $user = User::where('id', $id)->first();
        $levels = Level::where('level_status', '=', 'Y')->orderBy('level_rank', 'asc')->get();
        
        return view('admin.editaccount', [
            'users_data' => $user,
            'levels' => $levels
            ]);
    }
    //刪除會員
    public function delectAccount($id)
    {
        $checkDetail = User::join('detail', 'users.id', '=', 'detail.user_id')->where([['detail_status', '=', '0'],['id', '=', $id]])->count();
        if($checkDetail){
            return redirect()->back()->withErrors('此帳號尚有訂單')->withInput(); 
        }
        $user = User::where('id', $id)->first();
        $user->status = 'D';
        $user->updated_at = date("Y-m-d H:i:s"); ;
        $user->save();

        return redirect()->intended('admin/account');
    }

    //修改會員資料
    public function editAccount(Request $request)
    {   
        $user = User::where('id', $request->input('id'))->first();
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
            'email' => 'email|required|unique:users,email,'.$user->id,
            'phone' => 'required|numeric|regex:/^09\d{8}$/',
            'point' => 'required|numeric',
            'level' => 'required|numeric'

        ], [
            'name.regex' => __('shop.nameregex'),
            'email.email' => __('shop.emailvalidation'),
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->point = $request->input('point');
        $user->status = $request->input('status');
        $user->level = $request->input('level');
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return redirect()->intended('admin/account');
    }

    public function searchAccount(Request $request)
    {
        $query = $request->input('query');
        $user = User::where('name', 'LIKE', '%'.$query.'%')->get();

        return view('admin.account', ['users_data' => $user]);
    }
}
