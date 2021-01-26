<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Return_detail;
use App\detail;
use App\Detail_item;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ReturnController extends Controller
{
    public function index() 
    {
        $id = Auth::id();
        $returnDetails = Return_detail::where('user_id', '=', $id)->get();
        $status = [
            0 => '未處理',
            1 => '同意',
            2 => '拒絕'
        ];

        return view('user.return', [
            'returnDetails' => $returnDetails,
            'status' => $status
            ]);
    }

    public function contentReturn($id)
    {
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
        return view('user.contentReturn', ['itmes' => $itme]); 
    }

}
