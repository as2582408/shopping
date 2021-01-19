<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use Storage;
use Auth;
use File;

class ProductsController extends Controller
{
    public function products()
    {
        $categories = Category::all();
        $products = Product::Join('category', 'products.product_category', '=', 'category.id')->get();

        return view('products.products', ['products' => $products ,'categories' => $categories]);
    }
    //新增商品頁面
    public function addProductsPage()
    {
        $categories = Category::all();

        return view('products.addProducts', ['category' => $categories]);
    }
    //新增商品
    public function addProducts(Request $request)
    {

        $this->validate($request, [
            'img' =>  'required|image',
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        //放置檔案
        $productsImg = $_FILES['img']['name'];
        $imgPath = '../storage/app/public';
        $request->file('img')->move($imgPath, $productsImg);
        $product_category = $request->input('category1');

        if($request->has('category2')) {
            $product_category = '';

            for( $i = 1; $i < 4; $i++) {
                $category = 'category'.$i;
                $product_category .= $request->input($category).',';
            }
        }

        $products = Product::create([
            'product_name' => $request->input('name'),
            'product_description' => $request->input('description'),
            'product_price' => $request->input('price'),
            'product_amount' => $request->input('amount'),
            'product_category' => $product_category,
            'product_img' => $productsImg,
            'product_create_time' => date("Y-m-d H:i:s"),
            'product_updata_time' => date("Y-m-d H:i:s")
        ]);
        $products->save();
        
        return redirect()->intended('admin/products');
    }

    //刪除商品
    public function delectProducts($id)
    {
        $product = Product::where('product_id', $id)->update([
            'product_status' => 'D', 
            'product_updata_time' => date("Y-m-d H:i:s")
        ]);

        return redirect()->intended('admin/products');
    }

    public function editProductsPage($id)
    {
        $categories = Category::all();
        $product = Product::where('product_id', $id)->first();

        return view('products.editProducts', ['category' => $categories, 'product' => $product]);
    }

    public function editProducts(Request $request)
    {

        if($request->hasFile('img')){
            $this->validate($request, [
                'img' =>  'required|image',
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'amount' => 'required|numeric',
                'description' => 'required'
            ]);
            //重新放置檔案，檔名不變
            $image_name = Product::where('product_id', $request->input('id'))->select('product_img')->first();
            $imgPath = '../storage/app/public';
            $request->file('img')->move($imgPath, $image_name->product_img);

            if($request->has('category2')) {
                $product_category = '';
    
                for( $i = 1; $i < 4; $i++) {
                    $category = 'category'.$i;
                    $product_category .= $request->input($category).',';
                }
            }

            $product = Product::where('product_id', $request->input('id'))->update([
                'product_name' => $request->input('name'),
                'product_description' => $request->input('description'),
                'product_price' => $request->input('price'),
                'product_amount' => $request->input('amount'),
                'product_category' => $product_category,
                'product_status' => $request->input('status'),
                'product_updata_time' => date("Y-m-d H:i:s")
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'amount' => 'required|numeric',
                'description' => 'required'
            ]);
            if($request->has('category2')) {
                $product_category = '';
    
                for( $i = 1; $i < 4; $i++) {
                    $category = 'category'.$i;
                    $product_category .= $request->input($category).',';
                }
            }

            $product = Product::where('product_id', $request->input('id'))->update([
                'product_name' => $request->input('name'),
                'product_description' => $request->input('description'),
                'product_price' => $request->input('price'),
                'product_amount' => $request->input('amount'),
                'product_category' => $product_category,
                'product_status' => $request->input('status'),
                'product_updata_time' => date("Y-m-d H:i:s")
            ]);
        }

        return redirect()->intended('admin/products');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'LIKE', '%'.$query.'%')->get();
        return view('products.products', ['products' => $products]);
    }
}