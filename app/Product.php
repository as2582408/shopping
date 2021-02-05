<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    //
    protected $table = 'products';

    protected $fillable = [
        'product_name', 'product_img', 'product_price','product_amount','product_create_time','product_updata_time','product_status','product_description','product_category'
    ];

    public function category()
    {
        return $this->belongsToMany('App\Category');
    }
}
