<?php

namespace App\Http\Controllers;

use App\Product;
use App\Cart;
use App\Category;
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
}
