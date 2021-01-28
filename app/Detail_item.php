<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_item extends Model
{
    public $timestamps = false;

    protected $table = 'detail_item';

    protected $fillable = [
        'item_detail_id', 'product_name', 'product_price', 'product_amount', 'product_retrun_amount'
    ];
    //
}
