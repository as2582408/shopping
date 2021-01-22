<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    protected $table = 'report';

    protected $fillable = [
        'user_id', 'report_description', 'report_reply', 'report_updata_time'
    ];
    //
}
