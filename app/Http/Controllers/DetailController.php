<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detail;
use App\User;
use App\Level;

class DetailController extends Controller
{
    public function index()
    {
        $details = Detail::select('detail.detail_id', 'detail.user_id', 'detail.detail_totail_price', 'users.name', 'detail.detail_status', 'detail.detail_shipment', 'detail.detail_create_time')
        ->join('users', 'users.id', '=', 'detail.user_id')
        ->get();

        $status_arr = ['未結束', '結束', '取消'];
        $shipment_arr = [1 => '未出貨',2 => '以出貨'];

        return view('detail.detail', [
            'details' => $details,
            'status' => $status_arr,
            'shipment' => $shipment_arr
            ]);
    }
    //
    public function editDetailPage($id)
    {
        $detail = Detail::where('detail_id', $id)->join('users', 'users.id', '=', 'detail.user_id')->first();
        
        return view('detail.editdetail', ['detail' => $detail]);
    }

    public function editDetail(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|max:255',
            'phone' => 'required|numeric|regex:/^09\d{8}$/',
        ]);

        Detail::where('detail_id', $request->input('id'))->update([
            'user_phone' => $request->input('phone'),
            'user_address' => $request->input('address')
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
            'remarks' => 'required|max:255',
        ]);

        Detail::where('detail_id', '=', $request->input('id'))->update([
            'detail_remarks' => $request->input('remarks'),
            'detail_status' => '2'
        ]);

        $this->returnPoint($request->input('id'));

        return redirect()->intended('/admin/detail');
    }

    //額度歸還
    public function returnPoint($detailId)
    {
        //結束訂單後才會取得禮金與累積金額，取消訂單不對禮金做動作
        $detailData = Detail::select('user_id', 'detail_shopping_point')->where('detail_id', '=', $detailId)->first();

        User::where('id', '=', $detailData->user_id)->increment('point', $detailData->detail_shopping_point);
    }

    public function shipmentDetail($id)
    {
        Detail::where('detail_id', '=', $id)->update(['detail_shipment' => '2']);

        return redirect()->intended('/admin/detail');
    }

    public function endDetail($id)
    {
        Detail::where('detail_id', '=', $id)->update(['detail_status' => '1' ]);
        $userData = Detail::select('user_id', 'detail_totail_price', 'detail_gift_money')->where('detail_id', '=', $id)->first();
        //刷新累計金額與禮金
        if($userData->detail_gift_money > 0) {
            User::where('id', '=', $userData->user_id)->increment('point', $userData->detail_gift_money);
        }
        User::where('id', '=', $userData->user_id)->increment('accumulation_point', $userData->detail_totail_price );
        
        //刷新會員等級
        $newAccumulation = User::select('accumulation_point')->where('id', '=', $userData->user_id)->first();
        $newLevel = Level::select('level_rank')->where('level_threshold', '<=', $newAccumulation->accumulation_point)->orderBy('level_threshold', 'desc')->first();
        User::where('id', '=', $userData->user_id)->update(['level' => $newLevel->level_rank]);

        return redirect()->intended('/admin/detail');
    }
}
