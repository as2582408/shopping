<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        return view('discount.discount',[ 'discounts' => $discounts ]);
    }

    public function editDiscountPage($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/admin/discount');
        }
        $discount = Discount::where('discount_id', '=', $id)->first();

        return view('discount.editdiscount',[ 'discount' => $discount ]);
    }

    public function editDiscount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
            'level' => 'required|numeric|min:0|integer',
            'threshold' => 'required|numeric|min:0|digits_between:0,10',
            'gift' => 'required|numeric'
        ]);
        //檢查折扣數字，不能為負數或超出10位數
        if((float)$request->gift <= 0) {
            return redirect()->back()->withErrors(__('shop.discount error1'));
        }
        if($request->gift > 9999999999) {
            return redirect()->back()->withErrors(__('shop.discount error2'));
        }
        Discount::where('discount_id', '=', $request->input('id'))->update([
            'discount_name' => $request->input('name'),
            'level' => $request->input('level'),
            'discount_threshold' => $request->input('threshold'),
            'discount_gift' => $request->input('gift'),
            'discount_status' => $request->input('status'),
            'discount_updata_time' => date("Y-m-d H:i:s")
        ]);
        
        return redirect()->intended('/admin/discount');
    }

    public function delDiscount($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/admin/discount');
        }
        Discount::where('discount_id', '=', $id)->update([
            'discount_status' => 'D',
            'discount_updata_time' => date("Y-m-d H:i:s")
        ]);

        return redirect()->intended('/admin/discount');
    }

    public function addDiscountPage()
    {
        return view('discount.adddiscount');
    }

    public function addDiscount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/|unique:discount,discount_name',
            'level' => 'required|numeric|min:0|integer',
            'threshold' => 'required|numeric|min:0|digits_between:0,10',
            'gift' => 'required|numeric'
        ]);
        //檢查折扣數字，不能為負數或超出10位數
        if((float)$request->gift <= 0) {
            return redirect()->back()->withErrors(__('shop.discount error1'));
        }
        if($request->gift > 9999999999) {
            return redirect()->back()->withErrors(__('shop.discount error2'));
        }
        Discount::create([
            'discount_name' => $request->input('name'),
            'level' => $request->input('level'),
            'discount_threshold' => $request->input('threshold'),
            'discount_gift' => $request->input('gift'),
            'discount_create_time' => date("Y-m-d H:i:s"),
            'discount_updata_time' => date("Y-m-d H:i:s")
        ])->save();

        return redirect()->intended('/admin/discount');
    }
    //
}
