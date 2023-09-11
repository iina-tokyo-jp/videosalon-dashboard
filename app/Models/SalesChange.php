<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesChange extends Model
{
    protected $connection = 'pgsql2';

    protected $table = 'saleschanges';

    protected $fillable = [
        'site_id',
        'appraiser_id',
        'sales_date',
        'sales_amount',
        'detail',
        'add_date'
    ];

    protected $dates = [
        'sales_date',
        'add_date',
    ];

    public $timestamps = false;
}
