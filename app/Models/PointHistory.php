<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{

    protected $table = 'pointhistories';

    protected $fillable = [
        'site_id',
        'user_id',
        'user_name',
        'user_account',
        'point',
        'reason',
        'detail',
        'add_date',
        'worker_id',
        'worker_name',
        'worker_account',
    ];

    protected $dates = ['add_date'];

    public $timestamps = false;


}
