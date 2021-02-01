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
        $product = Product::where('product_id', '=', $id)->first();

        return view('shop.show', ['product' => $product]);
    }
    //加入購物車
    public function addcart(Request $request)
    {
        $product = Product::where('product_id', '=', $request->id)->first();

        if($request->quantity > $product->product_amount)
        {
            return redirect()->back()->withSuccessMessage('數量超出庫存');
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
                'cart_product_price' => $request->price,
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
                'cart_product_price' => $price,
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
        $totalPrice = 0;
        $nameArr = [
            'discountName' => '使用折扣',
            'discountGift' => '折扣比率',
            'discountPrice' => '折扣後價格',
            'useGift' => '使用禮金',
            'useGiftBefore' => '使用後禮金',
            'endPrice' =>   '應付價格',
            'discountGift' => '可獲得禮金',
        ];
        foreach($products as $product)
        {
            $totalPrice += ($product->product_price * $product->cart_product_amount);
        }

        if($request->point == 1 && $discount->discount_gift < 1) {
            $discountPrice = $totalPrice * $discount->discount_gift;

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
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//折扣比率
                'discountPrice' => $discountPrice, //折扣後價格
                'useGift' => $useGift, //使用禮金
                'useGiftBefore' => $useGiftBefore,//使用後禮金
                'endPrice' =>   $endPrice//應付價格
            ];
            $nameArr['discountGift'] = "折價比率";
        }

        if($request->point == 1 && $discount->discount_gift > 1) {

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
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//可獲得禮金
                'useGift' => $useGift, //使用禮金
                'useGiftBefore' => $useGiftBefore,//使用後禮金
                'endPrice' =>   $endPrice//應付價格
            ];
        }

        if($request->point == 2 && $discount->discount_gift < 1) {
            $discountPrice = $totalPrice * $discount->discount_gift;

            $checkout = [
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//折扣比率
                'discountPrice' => $discountPrice, //折扣後價格
                'discountPrice' =>   $discountPrice//應付價格
            ];
            $nameArr['discountGift'] = "折價比率";
        }

        if($request->point == 2 && $discount->discount_gift > 1) {

            $checkout = [
                'discountName' => $discount->discount_name, //使用折扣
                'discountGift' => $discount->discount_gift,//可獲得禮金
                'endPrice' =>   $totalPrice//應付價格
            ];
        }

        return view('shop.checking', [
            'checkout' => $checkout,
            'discountId' => $request->discount,
            'name' => $nameArr
        ]);
    }

    public function checkout(Request $request) {

        $userData = User::where('id', '=', Auth::id())->first();
        $discountGift = ($request->input('discountGift') > 1 )  ? $request->input('discountGift') : 0;
        $giftPoint = ($request->input('useGift') > 0) ? $request->input('useGift') : 0;
        $newPoint = ($request->input('useGift') > 0) ? $request->input('useGiftBefore') : $userData->point;

        User::where('id', '=', Auth::id())->decrement('point', $giftPoint);

        $detailId = Detail::insertGetId([
            'user_id' => Auth::id(),
            'detail_discount_id'  => $request->input('discountId'),
            'detail_totail_price' => $request->input('endPrice'),
            'detail_status' => '0',
            'detail_shipment' => '1',
            'detail_updata_time' => date("Y-m-d H:i:s"),
            'detail_create_time' => date("Y-m-d H:i:s"),
            'user_phone' => $userData->phone,
            'user_address' => $userData->address,
            'detail_shopping_point' => $giftPoint,
            'detail_gift_money' => $discountGift,
            'detail_description' => '',
            'detail_remarks' => ''
        ]);
        //紀錄消費log
        if($giftPoint > 0) {
            Point_log::create([
                'log_user_id' => $userData->id,
                'log_detail' => $detailId,
                'log_change_gold' => $giftPoint,
                'log_new_gold' => $newPoint,
                'log_type' => '1',
                'log_time' => date("Y-m-d H:i:s")
            ])->save();
        }
        //將購物車商品放置訂單細項 庫存數量扣除
        $products = Cart::join('products', 'cart.product_id', '=', 'products.product_id')->where('user_id', '=', $userData->id)->get();
        foreach($products as $product)
        {
            //寫入細單如是折扣優惠 需要打折後再寫入
            $price = ($request->discountGift < 1) ? $product->product_price * $request->discountGift : $product->product_price;
            
            Detail_item::create([
                'item_detail_id' => $detailId,
                'product_name' => $product->product_name,
                'product_price' => $price,
                'product_amount' => $product->cart_product_amount,
                'product_retrun_amount' => 0
            ])->save();
            Product::where('product_id', '=', $product->product_id)->decrement('product_amount', $product->cart_product_amount);
        }
        //刪除購物車商品
        Cart::where('user_id', '=', $userData->id)->delete();
        
        return redirect()->intended('detail');
    }
}
