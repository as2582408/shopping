<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Level;
use App\Point_log;
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
    
    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'email|required',
            'password' => 'required|min:6'
        ], [
            'email.email'  => __('shop.emailvalidation'),
            'password.min' => __('shop.passwordmin')
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) && $user->admin == 'Y') {
            $user->login_time = date("Y-m-d H:i:s");
            $user->save();            
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

    //後台會員主頁面
    public function account()
    {
        $user = User::all();
        $status = [
            'Y' => __('shop.Enable'),
            'N' => __('shop.Suspension'),
            'D' => __('shop.Delete')
        ];

        return view('admin.account', [
            'users_data' => $user,
            'status' => $status
            ]);
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
    //後台刪除會員
    public function delectAccount($id)
    {
        $checkDetail = User::join('detail', 'users.id', '=', 'detail.user_id')->where([
            ['detail_status', '=', '0'],
            ['id', '=', $id]])->count();

        if ($checkDetail){
            return redirect()->back()->withErrors(__('shop.orderIsHas')); 
        }

        $user = User::where('id', $id)->first();
        $user->status = 'D';
        $user->email = '(D)'.$user->email;
        $user->name = '(D)'.$user->name;
        $user->updated_at = date("Y-m-d H:i:s"); ;
        $user->save();

        return redirect()->intended('admin/account')->withSuccessMessage(__('delete Success'));
    }

    //後台修改會員資料
    public function editAccount(Request $request)
    {   
        $user = User::where('id', $request->input('id'))->first();
        if($user->status == 'D') {
            $this->validate($request, [
                'name' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/',
                'email' => 'required',
                'phone' => 'required|numeric|regex:/^09\d{8}$/',
                'point' => 'required|numeric',
                'level' => 'required|numeric'
    
            ], [
                'name.regex' => __('shop.nameregex'),
                'email.email' => __('shop.emailvalidation'),
            ]);
        } else {
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
        }

        $newName = $request->input('name');
        $newemail = $request->input('email');
        //修改刪除狀態時，進行名稱的還原或修改，並檢查是否已有重複的帳號
        if($request->input('status') == 'D' && $user->status != 'D') {
            $newName = '(D)'.$request->input('name');
            $newemail = '(D)'.$request->input('email');
        } elseif($request->input('status') != 'D' && $user->status == 'D') {
            $newName = str_replace('(D)', '', $request->input('name'));
            $newemail = str_replace('(D)', '', $request->input('email'));

            $userCheck = User::where('email', $newemail)->first();
            if(isset($userCheck)) {
                return redirect()->intended('admin/account')->withErrors(__('shop.emailunique'));
            }
        }
        if($user->point != $request->input('point'))
        {
            $cheageMoney = $request->input('point') - $user->point;

            Point_log::create([
                'log_user_id' => $user->id,
                'log_detail' => '0',
                'log_change_gold' => $cheageMoney,
                'log_new_gold' => $request->input('point'),
                'log_type' => '6',
                'log_time' => date("Y-m-d H:i:s")
            ])->save();
        };
        $user->name = $newName;
        $user->email = $newemail;
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->point = $request->input('point');
        $user->status = $request->input('status');
        $user->level = $request->input('level');
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return redirect()->intended('admin/account')->withSuccessMessage(__('shop.edit Success'));
    }

    public function searchAccount(Request $request)
    {
        $query = $request->input('query');
        $user = User::where('name', 'LIKE', '%'.$query.'%')->get();

        $status = [
            'Y' => __('shop.Enable'),
            'N' => __('shop.Suspension'),
            'D' => __('shop.Delete')
        ];

        return view('admin.account', [
            'users_data' => $user,
            'status' => $status
        ]);
    }

    public function setPasswordPage($id)
    {
        return view('admin.setpassword', ['userId' => $id]);
    }

    public function setPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|regex:/^.*(?=.*[a-z])(?=.*[0-9]).*$/',
        ], [
            'password.min' => __('shop.passwordmin'),
            'password.regex' => __('shop.passwordregex')
        ]);
        $user = User::find($request->id);
        $user->password = bcrypt($request->input('password'));
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return redirect('admin/account');
    }
}
