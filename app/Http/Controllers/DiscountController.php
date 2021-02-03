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
        $discount = Discount::where('discount_id', '=', $id)->first();

        return view('discount.editdiscount',[ 'discount' => $discount ]);
    }

    public function editDiscount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'level' => 'required|numeric|min:0|integer',
            'threshold' => 'required|numeric|min:0',
            'gift' => 'required|numeric'
        ]);
        if((float)$request->gift <= 0) {
            return redirect()->back()->withErrors('優惠比例請輸入大於0的數字');
        }
        Discount::where('discount_id', '=', $request->input('id'))->update([
            'discount_name' => $request->input('name'),
            'level' => $request->input('level'),
            'discount_threshold' => $request->input('threshold'),
            'discount_gift' => $request->input('gift'),
            'discount_updata_time' => date("Y-m-d H:i:s")
        ]);
        
        return redirect()->intended('/admin/discount');
    }

    public function delDiscount($id)
    {
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
            'name' => 'required|max:255',
            'level' => 'required|numeric',
            'threshold' => 'required|numeric',
            'gift' => 'required|numeric'
        ]);

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
