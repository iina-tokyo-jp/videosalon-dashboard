<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    protected $connection = 'pgsql2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'status',
        'history_id',
        'body',
        'read_flag',
        'user_id',
        'user_name',
        'user_account',
        'appraiser_id',
        'appraiser_name',
        'appraiser_account',
        'authorizer_id',
        'authorizer_name',
        'authorizer_account',
        'authorizer_report',
        'add_date',
        'pub_date'
    ];

    protected $dates = [
        'add_date',
        'pub_date'
    ];

    public $timestamps = false;
}
