<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public $timestamps = false;

    protected $table = 'level';
    //
    protected $fillable = [
        'level_name', 'level_rank', 'level_rank', 'level_threshold', 'discount_status'
    ];
}
