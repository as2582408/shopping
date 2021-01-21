<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point_log extends Model
{
    public $timestamps = false;

    protected $table = 'point_log';

    protected $fillable = ['log_user_id', 'log_detail', 'log_change_gold', 'log_new_gold', 'log_type', 'log_time'];
    //
}
