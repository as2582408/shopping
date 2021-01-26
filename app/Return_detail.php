<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Return_detail extends Model
{
    public $timestamps = false;

    protected $table = 'return_detail';
    //
    protected $fillable = [
        'detail_id','user_id','return_itme_id','return_create_time','return_updata_time','return_status','return_reply','return_message'
    ];

}
