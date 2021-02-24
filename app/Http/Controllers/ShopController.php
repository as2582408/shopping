<?php

namespace App\Http\Controllers;

use App\Product;
use App\Cart;
use App\Category;
use App\Detail;
use App\Detail_item;
use App\Discount;
use App\User;
use App\Point_log;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::where([
            ['product_status', '=', 'Y'],
            ['product_amount', '>', 0] 
            ])->get();
        $category = Category::all();

        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => '',
            'search' => ''
            ]);
    }
    //商品頁面
    public function show($id)
    {
        if(!is_numeric($id)) {
            return redirect('/');
        }
        $product = Product::where('product_id', '=', $id)->first();
        if(!$product) {
            return redirect('/');
        }

        return view('shop.show', ['product' => $product]);
    }
    //加入購物車
    public function addcart(Request $request)
    {
        $product = Product::where('product_id', '=', $request->id)->first();

        if($request->quantity > $product->product_amount)
        {
            return redirect()->back()->withSuccessMessage(__('shop.product Over'));
        }

        $cart = Cart::where([
            ['user_id', '=', Auth::id()],
            ['product_id', '=', $request->id]
        ])->first();

        if(isset($cart)) {
            $cart = Cart::where([
                ['user_id', '=', Auth::id()],
                ['product_id', '=', $request->id]
            ])->increment('cart_product_amount', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->id,
                'cart_product_amount' => $request->quantity,
                'cart_input_time' => date("Y-m-d H:i:s")
            ])->save();
        }

        return redirect()->intended('shop')->withSuccessMessage('成功加入購物車');
    }
    //快速加入購物車
    public function quicklyAddCart($id, $price)
    {
        $cart = Cart::where([
            ['user_id', '=', Auth::id()],
            ['product_id', '=', $id]
        ])->first();

        if(isset($cart)) {
            $cart = Cart::where([
                ['user_id', '=', Auth::id()],
                ['product_id', '=', $id]
            ])->increment('cart_product_amount');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'cart_product_amount' => '1',
                'cart_input_time' => date("Y-m-d H:i:s")
            ])->save();
        }

        return redirect()->back()->withSuccessMessage('成功加入購物車');
    }
    //搜尋
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where([
            ['product_name', 'LIKE', '%'.$query.'%'],
            ['product_status', '=', 'Y'],
            ['product_amount', '>', 0] 
            ])->get();

        $category = Category::all();
        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => '',
            'search' => $query
            ]);
    }
    //選擇分類
    public function selectCategory($id)
    {
        if(!is_numeric($id)) {
            return redirect('/');
        }
        $products = Product::where([
            ['product_category', 'LIKE', '%'.$id.'%'],
            ['product_status', '=', 'Y'],
            ['product_amount', '>', 0] 
            ])->get();

        $category = Category::all();
        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => $id,
            'search' => ''
            ]);
    }
    //正常排序
    public function orderBy($orderby, $type)
    {
        $product = new Product;
        $product->where([
            ['product_status', '=', 'Y'],
            ['product_amount', '>', 0],
            ]);
        if($type == 'price') {
            $products = $product->orderBy('product_price', $orderby)->get();
        }

        if($type == 'time') {
            $products = $product->orderBy('product_create_time', $orderby)->get();
        }

        $category = Category::all();

        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => '',
            'search' => ''
            ]);
    }
    //分類排序
    public function orderByCategory($orderby, $type, $categoryId)
    {
        $product = new Product;
        if($type == 'price') {
            $products = $product->where([
                ['product_status', '=', 'Y'],
                ['product_amount', '>', 0],
                ['product_category', 'LIKE', '%'.$categoryId.'%'],
                ])->orderBy('product_price', $orderby)->get();
        }

        if($type == 'time') {
            $products = $product->where([
                ['product_status', '=', 'Y'],
                ['product_amount', '>', 0],
                ['product_category', 'LIKE', '%'.$categoryId.'%'],
                ])->orderBy('product_create_time', $orderby)->get();
        }

        $category = Category::all();

        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => $categoryId,
            'search' => ''
            ]);
    }
    //搜尋排序
    public function orderBySearch($orderby, $type, $search)
    {
        $product = new Product;
        if($type == 'price') {
            $products = $product->where([
                ['product_name', 'LIKE', '%'.$search.'%'],
                ['product_status', '=', 'Y'],
                ['product_amount', '>', 0],
                ])->orderBy('product_price', $orderby)->get();
        }

        if($type == 'time') {
            $products = $product->where([
                ['product_name', 'LIKE', '%'.$search.'%'],
                ['product_status', '=', 'Y'],
                ['product_amount', '>', 0],
                ])->orderBy('product_create_time', $orderby)->get();
        }

        $category = Category::all();

        return view('shop.shopIndex', [
            'products' => $products,
            'categories' => $category,
            'categoryId' => '',
            'search' => $search
            ]);
    }

    public function cart()
    {
        $totalPrice = 0;
        $userData = User::where('id', '=', Auth::id())->first();
        $products = Cart::join('products', 'cart.product_id', '=', 'products.product_id')->where('user_id', '=', $userData->id)->get();
        foreach($products as $product)
        {
            $totalPrice += ($product->product_price * $product->cart_product_amount);
        }
        
        $discounts = Discount::where([
            ['level', '<=' ,$userData->level],
            ['discount_threshold', '<=', $totalPrice],
            ['discount_status', '=', 'Y']
        ])->get();
        return view('shop.cart', [
            'userPoint' => $userData->point,
            'products' => $products,
            'totalPrice' => $totalPrice,
            'discounts' => $discounts
            ]);
    }

    public function removeCart($id)
    {
        if(!is_numeric($id)) {
            return redirect()->back();
        }
        Cart::where([
            ['user_id', '=', Auth::id()],
            ['product_id', '=', $id]
        ])->delete();
        
        return redirect()->back();
    }

    public function checking(Request $request)
    {
        $discount = Discount::where('discount_id', '=', $request->discount)->first();
        $userData = User::where('id', '=', Auth::id())->first();
        $products = Cart::join('products', 'cart.product_id', '=', 'products.product_id')->where('user_id', '=', $userData->id)->get();
        $amountArr = implode(",", $request->amount);
        $totalPrice = 0;
        $i = 0;

        $nameArr = [
            'totalPrice' => __('shop.total'),
            'discountName' => __('shop.discountName'),
            'discountPrice' => __('shop.discountPrice'),
            'useGift' => __('shop.useGift'),
            'useGiftBefore' => __('shop.useGiftBefore'),
            'endPrice' =>   __('shop.endPrice'),
            'discountGift' => __('shop.discountGift'),
        ];
        //計算總價
        foreach($products as $product) {
            $newAmount = explode('_', $request->amount[$i]);
            $totalPrice += ($product->product_price * $newAmount[1]);
            $i++;
        }
        //無使用折扣，無購物金
        if($request->point == 2 && $request->discount == 0) {
            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'discountName' => '無', //使用折扣
                'endPrice' =>  '$'.$totalPrice//應付價格
            ];
        }
        //無使用折扣，使用購物金
        if($request->point == 1 && $request->discount == 0)
        {
            if($totalPrice >= $userData->point){
                $useGiftBefore = 0;
                $useGift = $userData->point;
                $endPrice = ($totalPrice - $userData->point);
            } else {
                $useGift = $totalPrice;
                $useGiftBefore = ($userData->point - $totalPrice);
                $endPrice = 0;
            };

            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'useGift' => $useGift, //使用禮金
                'useGiftBefore' => $useGiftBefore,//使用後禮金
                'endPrice' =>   '$'.$endPrice//應付價格
            ];
        }

        if(isset($discount) && $request->point == 1 && $discount->discount_gift < 1) {
            $discountPrice = ceil($totalPrice * $discount->discount_gift);

            if($discountPrice >= $userData->point){
                $useGiftBefore = 0;
                $useGift = $userData->point;
                $endPrice = ($discountPrice - $userData->point);
            } else {
                $useGift = $discountPrice;
                $useGiftBefore = ($userData->point - $discountPrice);
                $endPrice = 0;
            };

            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//折扣比率
                'discountPrice' => '$'.$discountPrice, //折扣後價格
                'useGift' => $useGift, //使用禮金
                'useGiftBefore' => $useGiftBefore,//使用後禮金
                'endPrice' =>   '$'.$endPrice//應付價格
            ];
            $nameArr['discountGift'] = __('shop.discountGift2');
        }

        if(isset($discount) && $request->point == 1 && $discount->discount_gift > 1) {

            if($totalPrice >= $userData->point){
                $useGiftBefore = 0;
                $useGift = $userData->point;
                $endPrice = ($totalPrice - $userData->point);
            } else {
                $useGift = $totalPrice;
                $useGiftBefore = ($userData->point - $totalPrice);
                $endPrice = 0;
            };

            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//可獲得禮金
                'useGift' => $useGift, //使用禮金
                'useGiftBefore' => $useGiftBefore,//使用後禮金
                'endPrice' =>   '$'.$endPrice//應付價格
            ];
        }

        if(isset($discount) && $request->point == 2 && $discount->discount_gift < 1) {
            $discountPrice = ceil($totalPrice * $discount->discount_gift);

            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//折扣比率
                'discountPrice' => '$'.$discountPrice, //折扣後價格
                'endPrice' =>   '$'.$discountPrice//應付價格
            ];
            $nameArr['discountGift'] = __('shop.discountGift2');
        }

        if(isset($discount) && $request->point == 2 && $discount->discount_gift > 1) {

            $checkout = [
                'totalPrice' => '$'.$totalPrice,
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//可獲得禮金
                'endPrice' =>   '$'.$totalPrice//應付價格
            ];
        }

        return view('shop.checking', [
            'user' => $userData,
            'checkout' => $checkout,
            'discountId' => $request->discount,
            'name' => $nameArr,
            'amount' => $amountArr
        ]);
    }

    public function checkout(Request $request) {

        $phonePattern = "/^09\d{8}$/";
        $addressPattern = "/^[\x7f-\xffA-Za-z0-9 ()（）\s]+$/";

        if(!preg_match($phonePattern, $request->phone, $matches)) {
            return redirect('shop/cart');
        }
        if(!preg_match($addressPattern, $request->address, $matches)) {
            return redirect('shop/cart');
        }
        $userData = User::where('id', '=', Auth::id())->first();
        $discountGift = ($request->input('discountGift') > 1 )  ? $request->input('discountGift') : 0;
        $giftPoint = ($request->input('useGift') > 0) ? $request->input('useGift') : 0;
        $newPoint = ($request->input('useGift') > 0) ? $request->input('useGiftBefore') : $userData->point;
        $endPrice = str_replace('$', '', $request->input('endPrice'));
        $newAmount = $request->input('amount');
        $amountArr = explode(',', $newAmount);
        User::where('id', '=', Auth::id())->decrement('point', $giftPoint);

        $detailId = Detail::insertGetId([
            'user_id' => Auth::id(),
            'detail_discount_id'  => $request->input('discountId'),
            'detail_totail_price' => $endPrice,
            'detail_status' => '0',
            'detail_shipment' => '1',
            'detail_updata_time' => date("Y-m-d H:i:s"),
            'detail_create_time' => date("Y-m-d H:i:s"),
            'user_phone' => $request->phone,
            'user_address' => $request->address,
            'detail_shopping_point' => $giftPoint,
            'detail_gift_money' => $discountGift,
            'detail_description' => '',
            'detail_remarks' => ''
        ]);
        //紀錄消費log
        if ($giftPoint > 0) {
            Point_log::create([
                'log_user_id' => $userData->id,
                'log_detail' => $detailId,
                'log_change_gold' => -$giftPoint,
                'log_new_gold' => $newPoint,
                'log_type' => '1',
                'log_time' => date("Y-m-d H:i:s")
            ])->save();
        }

        foreach ($amountArr as  $amount) {
            $amount = explode('_', $amount);
            Cart::where([['user_id', '=', $userData->id], ['product_id', '=', $amount[0]]])->update(['cart_product_amount' => $amount[1]]);
        }
        //將購物車商品放置訂單細項 庫存數量扣除
        $products = Cart::join('products', 'cart.product_id', '=', 'products.product_id')->where('user_id', '=', $userData->id)->get();
        foreach($products as $product)
        {
            //寫入細單如是折扣優惠 需要打折後再寫入
            $price = ($request->discountGift < 1 && $request->discountGift != 0) ? ceil($product->product_price * $request->discountGift) : $product->product_price;
            
            Detail_item::create([
                'item_detail_id' => $detailId,
                'product_name' => $product->product_name,
                'product_price' => $price,
                'product_amount' => $product->cart_product_amount,
                'product_retrun_amount' => 0
            ])->save();
            
            $productAmount = Product::where('product_id', '=', $product->product_id)->select('product_amount')->first();
            if(($productAmount->product_amount - $product->cart_product_amount) <= 0) {
                Product::where('product_id', '=', $product->product_id)->decrement('product_amount', $product->cart_product_amount);
                Cart::where('product_id', '=', $product->product_id)->delete();
            }
        }
        //刪除購物車商品
        Cart::where('user_id', '=', $userData->id)->delete();
        
        return redirect()->intended('detail');
    }
}
