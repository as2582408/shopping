<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_reply extends Model
{
    public $timestamps = false;

    protected $table = 'report_reply';
    
    protected $fillable = [
        'reply_id', 'reply', 'reply_time'
    ];
}
