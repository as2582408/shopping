<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('category.category', ['categories' => $categories]);
    }
    
    public function editCategoryPage($id)
    {
        $category = Category::where('id' , '=', $id)->first();
        return view('category.editcategory', ['categories' => $category]);
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        Category::where('id', '=', $request->input('id'))->update([
            'category_name' => $request->input('name')
        ]);

        return redirect()->intended('admin/category');
    }

    public function delCategory($id)
    {
        $products = Product::where('product_category', 'LIKE' ,'%'.$id.'%')->get();
        $productsNum = count($products);

        for($i = 0; $i < $productsNum; $i++) {

            $product_category_arr = explode(',', $products[$i]['product_category']);
            $new_category = '';
            $categoryNum = count($product_category_arr);

            for($j = 0; $j < $categoryNum; $j++){
                if($product_category_arr[$j] != $id)
                {
                    if($j == $categoryNum-1) {
                        $new_category .= $product_category_arr[$j];
                    } else {
                        $new_category .= $product_category_arr[$j].',';
                    }
                }
            }
            //無分類時設置為未分類
            if ($new_category == '') {
                $new_category = '10';
            }
            $products[$i]['product_category'] = $new_category;
        }

        foreach($products as $product) {
            Product::where('product_id', '=', $product->product_id)->update([
                'product_category' => $product->product_category
            ]);
        }
        Category::where('id' , '=', $id)->delete();
        return redirect()->intended('admin/category');
    }

    public function addCategoryPage()
    {
        return view('category.addcategory');
    }

    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $category = Category::create([
            'category_name' => $request->input('name')
        ]);
        $category->save();
        return redirect()->intended('/admin/category');
    }
}
