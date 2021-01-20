<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public $timestamps = false;

    protected $table = 'discount';

    protected $fillable = [
        'discount_name', 'level', 'discount_threshold', 'discount_gift', 'discount_create_time', 'discount_updata_time', 'discount_status'
    ];
    //
}
