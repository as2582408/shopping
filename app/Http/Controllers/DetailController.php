<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Detail;
use App\User;
use App\Level;
use App\Point_log;
use App\Detail_item;
use App\Return_detail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DetailController extends Controller
{
    public function index()
    {
        $details = Detail::select('detail.detail_id', 'detail.user_id','detail_shopping_point' , 'detail.detail_totail_price', 'users.name', 'detail.detail_status', 'detail.detail_shipment', 'detail.detail_create_time')
        ->join('users', 'users.id', '=', 'detail.user_id')
        ->get();

        $status_arr = [__('shop.Not End'), __('shop.End'), __('shop.cancel')];
        $shipment_arr = [1 => __('shop.Not Send'),2 => __('shop.Send'),3 =>__('shop.RefundStatus')];

        return view('detail.detail', [
            'details' => $details,
            'status' => $status_arr,
            'shipment' => $shipment_arr
            ]);
    }

    public function editDetailPage($id)
    {
        $detail = Detail::where('detail_id', $id)->join('users', 'users.id', '=', 'detail.user_id')->first();
        $products = Detail_item::where('item_detail_id', '=', $id)->get();

        return view('detail.editdetail', [
            'detail' => $detail, 
            'products' => $products
            ]);
    }

    public function editDetail(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
            'phone' => 'required|numeric|regex:/^09\d{8}$/',
        ]);

        Detail::where('detail_id', $request->input('id'))->update([
            'user_phone' => $request->input('phone'),
            'user_address' => $request->input('address'),
            'detail_updata_time' => date("Y-m-d H:i:s")
        ]);
        return redirect()->intended('/admin/detail');
    }

    public function delDetailPage($id)
    {
        return view('detail.delectdetail', ['detailId' => $id]);
    }

    public function delDetail(Request $request)
    {
        $this->validate($request, [
            'remarks' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
        ]);

        Detail::where('detail_id', '=', $request->input('id'))->update([
            'detail_remarks' => $request->input('remarks'),
            'detail_status' => '2',
            'detail_updata_time' => date("Y-m-d H:i:s")

        ]);

        $this->returnPoint($request->input('id'));

        return redirect()->intended('/admin/detail');
    }

    //額度歸還
    public function returnPoint($detailId)
    {
        //結束訂單後才會取得禮金與累積金額，取消訂單不對禮金做動作
        $detailData = Detail::select('user_id', 'detail_shopping_point', 'detail_totail_price')->where('detail_id', '=', $detailId)->first();
        User::where('id', '=', $detailData->user_id)->increment('point', $detailData->detail_shopping_point);
        if ($detailData->detail_shopping_point > 0) {
            $log = User::select('point')->where('id', '=', $detailData->user_id)->first();
            Point_log::create([
                'log_user_id' => $detailData->user_id,
                'log_detail' => $detailId,
                'log_change_gold' => $detailData->detail_shopping_point,
                'log_new_gold' => $log->point,
                'log_type' => '3',
                'log_time' => date("Y-m-d H:i:s")
        ])->save();
        }
    }

    public function shipmentDetail($id)
    {
        Detail::where('detail_id', '=', $id)->update([
            'detail_shipment' => '2',
            'detail_updata_time' => date("Y-m-d H:i:s")
            ]);

        return redirect()->intended('/admin/detail');
    }

    public function endDetail($id)
    {
        Detail::where('detail_id', '=', $id)->update([
            'detail_status' => '1',
            'detail_updata_time' => date("Y-m-d H:i:s")
            ]);
        $userData = Detail::select('user_id', 'detail_totail_price', 'detail_shopping_point', 'detail_gift_money')->where('detail_id', '=', $id)->first();
        $total = ($userData->detail_shopping_point + $userData->detail_totail_price);

        //刷新累計金額與禮金
        if($userData->detail_gift_money > 0) {
            User::where('id', '=', $userData->user_id)->increment('point', $userData->detail_gift_money);
        }

        User::where('id', '=', $userData->user_id)->increment('accumulation_point', $total);

        //刷新會員等級
        $newAccumulation = User::select('point','accumulation_point')->where('id', '=', $userData->user_id)->first();
        $newLevel = Level::select('level_rank')->where('level_threshold', '<=', $newAccumulation->accumulation_point)->orderBy('level_threshold', 'desc')->first();
        User::where('id', '=', $userData->user_id)->update(['level' => $newLevel->level_rank]);

        if($userData->detail_gift_money > 0) {

            Point_log::create([
                'log_user_id' => $userData->user_id,
                'log_detail' => $id,
                'log_change_gold' => $userData->detail_gift_money,
                'log_new_gold' => $newAccumulation->point,
                'log_type' => '2',
                'log_time' => date("Y-m-d H:i:s")
            ])->save();
        }
        return redirect()->intended('/detail');
    }

    //會員訂單頁面
    public function userIndex()
    {
        $id = Auth::id();

        $details = Detail::where('user_id', '=', $id)->get();

        $status_arr = [__('shop.Not End'), __('shop.End'), __('shop.cancel')];
        $shipment_arr = [1 => __('shop.Not Send'),2 => __('shop.Send'),3 =>__('shop.RefundStatus')];

        return view('user.detail', [
            'details' => $details,
            'status' => $status_arr,
            'shipment' => $shipment_arr
            ]);
    }

    //會員編輯訂單頁面
    public function userEditDetailPage($id)
    {
        $detail = Detail::where('detail_id', $id)->join('users', 'users.id', '=', 'detail.user_id')->first();
        $products = Detail_item::where('item_detail_id', '=', $id)->get();

        return view('user.editdetail', [
            'detail' => $detail, 
            'products' => $products
            ]);
    }

    //會員編輯訂單
    public function userEditDetail(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
            'phone' => 'required|numeric|regex:/^09\d{8}$/',
        ]);

        Detail::where('detail_id', $request->input('id'))->update([
            'user_phone' => $request->input('phone'),
            'user_address' => $request->input('address'),
            'detail_updata_time' => date("Y-m-d H:i:s")
        ]);
        return redirect()->intended('/detail');
    }

    //
    public function userDelDetail($id)
    {
        Detail::where('detail_id', '=', $id)->update([
            'detail_status' => '2',
            'detail_updata_time' => date("Y-m-d H:i:s")
        ]);
        $this->returnPoint($id);
        
        return redirect()->intended('/detail');
    }

    //會員退貨頁面
    public function userReturnDetailPage($id)
    {
        $check = 0;
        $products = Detail_item::where('item_detail_id', '=', $id)->get();
        foreach($products as $product) {
            $check += ($product->product_amount - $product->product_retrun_amount);
        }
        return view('user.returndetail', ['products' => $products, 'check' => $check]);
    }
    
    //會員退貨
    public function userReturnDetail(Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
        ],[
            'product.required' => __('shop.productrequired')
        ]);
        
        $checks = $request->product;
        $product = Detail_item::whereIn('item_id', $checks)->first();
        $prCount = count($checks);
        $idArr = '';
        $messageStr ='';
        $userId = Auth::id();
        //檢查退貨數量
        foreach( $checks as $check) {
            if($request->$check == 0){
                return  redirect()->back()->withSuccessMessage(__('shop.quantityrequired'));;
            }       
        }

        for($i = 0; $i < $prCount; $i++) {
            if($i == $prCount-1) {
                $idArr .= $checks[$i];
                $mesId = $checks[$i];
                $messageStr .= $request->$mesId;
            } else {
                $idArr .= $checks[$i].',';
                $mesId = $checks[$i];
                $messageStr .= $request->$mesId.',';
            }
        }

        Return_detail::create([
            'detail_id' => $product->item_detail_id,
            'user_id'   =>  $userId,
            'return_itme_id' => $idArr,
            'return_create_time' => date("Y-m-d H:i:s"),
            'return_updata_time' => date("Y-m-d H:i:s"),
            'return_reply' => '',
            'return_message' => $messageStr
        ])->save();

        foreach( $checks as $check) {
            Detail_item::where('item_id', '=', $check)->increment('product_retrun_amount', $request->$check);
        }

        return redirect()->intended('/detail');
    }
    
}
