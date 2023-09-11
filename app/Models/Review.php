<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Review extends Model
{
    protected $connection = 'pgsql2';

    protected $dates = [
        'add_date',
        'pub_date'
    ];

    public $timestamps = false;
}
