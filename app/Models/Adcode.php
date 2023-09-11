<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class Adcode extends Model
{
    use OptimisticLockTrait;

    protected $table = 'adcodes';

    protected $fillable = [
        'site_id',
        'ad_code',
        'adagency_code',
        'status',
        'site_name',
        'start_date',
        'url',
        'unit_price',
        'banner',
        'add_date',
        'mod_date'
    ];

    protected $dates = [
        'start_date',
        'add_date',
        'mod_date',
        'last_date'
    ];

    public $timestamps = false;
}