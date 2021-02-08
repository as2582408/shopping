<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $timestamps = false;

    protected $table = 'cart';
    
    protected $fillable = [
        'user_id','product_id','cart_input_time','cart_product_amount'
    ];
}
