<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $timestamps = false;

    protected $table = 'detail';

    protected $fillable = [
        'detail_status', 'detail_shipment', 'detail_updata_time','detail_create_time', 'user_phone', 'user_address', 'detail_remarks' 
    ];
}