<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Return_detail;
use App\Detail;
use App\Detail_item;
use App\User;
use App\Discount;
use App\Level;
use App\Point_log;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ReturnController extends Controller
{
    public function index() 
    {
        $returnDetails = Return_detail::join('users', 'users.id', '=', 'return_detail.user_id')->get();
        $status = [
            0 => __('shop.Unprocessed'),
            1 => __('shop.Agree'),
            2 => __('shop.Disagree')
        ];
        return view('return.return', [
            'returnDetails' => $returnDetails,
            'status' => $status
            ]);
    }

    public function contentReturn($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/admin/return');
        }
        $returnDetail = Return_detail::where('return_id', '=', $id)->first();
        $itmeId = explode(",",$returnDetail->return_itme_id);
        $itmeAmount = explode(",",$returnDetail->return_message);
        $products = Detail_item::whereIn('item_id',$itmeId)->get();

        $itme = [];
        $i = 0;
        
        foreach($products as $product)
        {
            $itme[] = [
                'name' => $product->product_name,
                'price' => $product->product_price,
                'amount' => $itmeAmount[$i]
            ];
            $i++;
        }
        return view('return.content', [
            'itmes' => $itme,
            'return' => $returnDetail
            ]); 
    }

    public function refuseReturnPage($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/admin/return');
        }
        return view('return.refusereturn', ['returnId' => $id]);
    }
    //拒絕退貨
    public function refuseReturn(Request $request)
    {
        $this->validate($request, [
            'remarks' => 'required|max:255|regex:/^[\x7f-\xffA-Za-z0-9 ()（）!,:;\n\s]+$/',
        ]);
        //拒絕退貨理由
        $textToStore = nl2br(htmlentities($request->input('remarks'), ENT_COMPAT, 'UTF-8'));
        Return_detail::where('return_id', '=', $request->input('id'))->update([
            'return_reply' => $textToStore,
            'return_status' => '2',
            'return_updata_time' => date("Y-m-d H:i:s")
        ]);
        //計算退貨單的id與數量
        $returnItem = Return_detail::select('return_itme_id','return_message')->where('return_id', '=', $request->input('id'))->first();
        $itmeIdArr = explode(",",$returnItem->return_itme_id);
        $itmeAmountArr = explode(",",$returnItem->return_message);
        $i = 0;
        //回復訂單細項狀態
        foreach($itmeIdArr as $itmeId) {
            Detail_item::where('item_id', '=', $itmeId)->decrement('product_retrun_amount', $itmeAmountArr[$i]);;
            $i++;
        }

        return redirect()->intended('/admin/return');
    }

    public function agreeReturn($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/admin/return');
        }
        Return_detail::where('return_id', '=', $id)->update([
            'return_status' => '1',
            'return_updata_time' => date("Y-m-d H:i:s")
        ]);
        $returnData = Return_detail::where('return_id', '=', $id)->first();
        $detailData = Detail::where('detail_id', '=', $returnData->detail_id)->first();

        $itmeId = explode(",",$returnData->return_itme_id);
        $itmeAmount = explode(",",$returnData->return_message);
        
        $itmes = Detail_item::whereIn('item_id',$itmeId)->get();
        $changeMoney = 0;
        $i = 0;

        //計算退貨金額
        foreach($itmes as $item) {
            $changeMoney += $item->product_price * $itmeAmount[$i];
            $i++;
        }

        //還回額度並扣除累計金額
        User::where('id', '=', $detailData->user_id)->increment('point', $changeMoney);
        User::where('id', '=', $detailData->user_id)->decrement('accumulation_point', $changeMoney);

        $log = User::where('id', '=', $detailData->user_id)->first();
        Point_log::create([
            'log_user_id' => $detailData->user_id,
            'log_detail' => $detailData->detail_id,
            'log_change_gold' => $changeMoney,
            'log_new_gold' => $log->point,
            'log_type' => '4',
            'log_time' => date("Y-m-d H:i:s")
        ])->save();

        //扣除訂單價格
        if($detailData->detail_totail_price < $changeMoney){
            $detailChangeMoney = $changeMoney - $detailData->detail_totail_price;
            Detail::where('detail_id', '=', $returnData->detail_id)->decrement('detail_shopping_point', $detailChangeMoney, [
                'detail_totail_price' => '0', 
                'detail_updata_time' => date("Y-m-d H:i:s"),
                'detail_shipment' => '3'
            ]);
        } else {
            Detail::where('detail_id', '=', $returnData->detail_id)->decrement('detail_totail_price', $changeMoney, [
                'detail_updata_time' => date("Y-m-d H:i:s"),
                'detail_shipment' => '3'
            ]);
        }

        //計算優惠是否適用 不適用扣除訂單贈送禮金
        $newDetailPrice = $detailData->detail_totail_price + $detailData->detail_shopping_point - $changeMoney;
        $discount = Discount::where('discount_id', '=', $detailData->detail_discount_id)->first();
        //優惠如是折扣不需進入
        if(isset($discount) && $newDetailPrice < $discount->discount_threshold && $discount->discount_gift > 1) {
            User::where('id', '=', $detailData->user_id)->decrement('point', $detailData->detail_gift_money);
            Detail::where('detail_id', '=', $returnData->detail_id)->update([
                'detail_gift_money' => '0',
                'detail_updata_time' => date("Y-m-d H:i:s")
                ]);

            $log = User::where('id', '=', $detailData->user_id)->first();
            Point_log::create([
                'log_user_id' => $detailData->user_id,
                'log_detail' => $detailData->detail_id,
                'log_change_gold' => -$detailData->detail_gift_money,
                'log_new_gold' => $log->point,
                'log_type' => '5',
                'log_time' => date("Y-m-d H:i:s")
                ])->save();
        }

        //重新計算等級
        $newAccumulation = User::where('id', '=', $detailData->user_id)->first();
        $newLevel = Level::select('level_rank')->where('level_threshold', '<=', $newAccumulation->accumulation_point)->orderBy('level_threshold', 'desc')->first();
        if(!isset($newLevel->level_rank)){
            $level_rank = 0;
        } else {
            $level_rank = $newLevel->level_rank;
        }
        User::where('id', '=', $detailData->user_id)->update(['level' => $level_rank]);

        return redirect()->intended('/admin/return');
    }

    public function userIndex() 
    {
        $id = Auth::id();
        $returnDetails = Return_detail::where('user_id', '=', $id)->get();
        $status = [
            0 => __('shop.Unprocessed'),
            1 => __('shop.Agree'),
            2 => __('shop.Disagree')
        ];

        return view('user.return', [
            'returnDetails' => $returnDetails,
            'status' => $status
            ]);
    }

    public function userContentReturn($id)
    {
        if(!is_numeric($id)) {
            return redirect()->intended('/return');
        }
        $returnDetail = Return_detail::where('return_id', '=', $id)->first();
        $itmeId = explode(",",$returnDetail->return_itme_id);
        $itmeAmount = explode(",",$returnDetail->return_message);
        $products = Detail_item::whereIn('item_id',$itmeId)->get();

        $itme = [];
        $i = 0;
        
        foreach($products as $product)
        {
            $itme[] = [
                'name' => $product->product_name,
                'price' => $product->product_price,
                'amount' => $itmeAmount[$i]
            ];
            $i++;
        }
        return view('user.contentReturn', [
            'itmes' => $itme,
            'return' => $returnDetail
            ]); 
    }

}
