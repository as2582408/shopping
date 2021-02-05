<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Product_category;
use Storage;
use Auth;
use File;

class ProductsController extends Controller
{
    public function products()
    {
        $product = Product::find(1);
        $product->category()->detach();
        //$products = $product->category()->attach('1',['category_id' => '10','product_id' => '10']);
        //$products->where('product_id', '2')->get();

        echo($product->category()->get());
        die();
        //$cate = Product_category::join('category', 'product_category.category_id', '=', 'category.id')->get();
        $categories = Category::all();
        $products = Product::all();
        $category = [];

        $categories = json_decode($categories, true);
        $cate = json_decode($cate, true);

        $categoryName = [];
        $categoryArr = [];
        foreach ($categories as $categorys) {
                $categoryName[$categorys['id']] = $categorys['category_name'];
        }

        foreach($products as $product) {
            $categoryArr = explode(",", $product->product_category);
            $category += [ $product->product_id => $categoryArr ];
        }
        //$nu = count($cate);
        //for($i = 1; $i < $nu ;$i++)
        //{
        //    if($i == 1) {
        //        $categoryArr[] = $cate[0]['category_id'];
        //    }
//
        //    if($cate[$i]['product_id'] == $cate[$i-1]['product_id']){
        //        $categoryArr[] = $cate[$i]['category_id'];
        //    } else {
        //        $category += [ $cate[$i-1]['product_id'] => $categoryArr];
        //        $categoryArr = [];
        //        $categoryArr[] = $cate[$i]['category_id'];
        //    }
//
        //    if (($i+1) == $nu) {
        //        $category += [ $cate[$i-1]['product_id'] => $categoryArr];
        //    } 
        //}
        
        return view('products.products', [
            'products' => $products,
            'categoryName' => $categoryName,
            'productCategories' => $category
            ]);
    }
    //新增商品頁面
    public function addProductsPage()
    {
        $categories = Category::all();

        return view('products.addProducts', ['categories' => $categories]);
    }
    //新增商品
    public function addProducts(Request $request)
    {

        $this->validate($request, [
            'img' =>  'required|image',
            'name' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
            'price' => 'required|numeric|digits_between:0,10',
            'amount' => 'required|numeric|digits_between:0,10',
        ]);

        //放置檔案
        $productsImg = $_FILES['img']['name'];
        $imgPath = '../storage/app/public';
        $request->file('img')->move($imgPath, $productsImg);
        $productCategory = '10';

        $products = Product::insertGetId([
            'product_name' => $request->input('name'),
            'product_description' => $request->input('description'),
            'product_price' => $request->input('price'),
            'product_amount' => $request->input('amount'),
            'product_category' => $productCategory,
            'product_img' => $productsImg,
            'product_create_time' => date("Y-m-d H:i:s"),
            'product_updata_time' => date("Y-m-d H:i:s")
        ]);

        if($request->has('category')) {
            $categoryNum = count($request->category);
            for( $i = 0; $i < $categoryNum; $i++) {
                $productCategory = Product_category::create([
                    'product_id' => $products,
                    'category_id' => $request->category[$i]
                ]);
            }
            $productCategory->save();
        }
        
        return redirect()->intended('admin/products')->withSuccessMessage('新增商品成功');
    }

    //刪除商品
    public function delectProducts($id)
    {
        $product = Product::where('product_id', $id)->update([
            'product_status' => 'D', 
            'product_updata_time' => date("Y-m-d H:i:s")
        ]);

        return redirect()->intended('admin/products')->withSuccessMessage('刪除商品成功');
    }

    public function editProductsPage($id)
    {
        $categories = Category::all();
        $product = Product::where('product_id', $id)->first();

        $categoryArr = explode(",", $product->product_category);


        return view('products.editProducts', [
            'categories' => $categories,
            'product' => $product,
            'categoryArr' => $categoryArr 
            ]);
    }

    public function editProducts(Request $request)
    {

        if($request->hasFile('img')){
            $this->validate($request, [
                'img' =>  'required|image',
                'name' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
                'price' => 'required|numeric|digits_between:0,10',
                'amount' => 'required|numeric|digits_between:0,10',
                'description' => 'required|regex:/^[A-Za-z0-9\x7f-\xffA]+$/'
            ]);
            //重新放置檔案，檔名不變
            $image_name = Product::where('product_id', $request->input('id'))->select('product_img')->first();
            $imgPath = '../storage/app/public';
            $request->file('img')->move($imgPath, $image_name->product_img);

            $productCategory = '10';

            if($request->has('category')) {
                $productCategory = '';
                $categoryNum = count($request->category);

                for( $i = 0; $i < $categoryNum; $i++) {
                    if($i == $categoryNum - 1) {
                        $productCategory .= $request->category[$i];
                    } else {
                        $productCategory .= $request->category[$i].',';
                    }
                }
            }

            Product::where('product_id', $request->input('id'))->update([
                'product_name' => $request->input('name'),
                'product_description' => $request->input('description'),
                'product_price' => $request->input('price'),
                'product_amount' => $request->input('amount'),
                'product_category' => $productCategory,
                'product_status' => $request->input('status'),
                'product_updata_time' => date("Y-m-d H:i:s")
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|max:255',
                'price' => 'required|numeric|digits_between:0,10',
                'amount' => 'required|numeric|digits_between:0,10',
                'description' => 'required|regex:/^[A-Za-z0-9\x7f-\xffA]+$/'
            ]);
            $productCategory = '10';

            if($request->has('category')) {
                $productCategory = '';
                $categoryNum = count($request->category);

                for( $i = 0; $i < $categoryNum; $i++) {
                    if($i == $categoryNum - 1) {
                        $productCategory .= $request->category[$i];
                    } else {
                        $productCategory .= $request->category[$i].',';
                    }
                }
            }

            Product::where('product_id', $request->input('id'))->update([
                'product_name' => $request->input('name'),
                'product_description' => $request->input('description'),
                'product_price' => $request->input('price'),
                'product_amount' => $request->input('amount'),
                'product_category' => $productCategory,
                'product_status' => $request->input('status'),
                'product_updata_time' => date("Y-m-d H:i:s")
            ]);
        }

        return redirect()->intended('admin/products')->withSuccessMessage('修改成功');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        
        $categories = Category::all();
        $products = Product::where('product_name', 'LIKE', '%'.$query.'%')->get();
        $category = [];

        $categories = json_decode($categories, true);
        $categoryName = [];
        foreach ($categories as $categorys) {
                $categoryName[$categorys['id']] = $categorys['category_name'];
        }

        foreach($products as $product) {
            $categoryArr = explode(",", $product->product_category);
            $category += [ $product->product_id => $categoryArr ];
        }
        
        return view('products.products', [
            'products' => $products,
            'categoryName' => $categoryName,
            'productCategories' => $category
            ]);
    }
}
