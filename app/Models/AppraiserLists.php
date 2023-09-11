<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppraiserLists extends Model
{
    protected $table = 'appraiser_lists';

    protected $fillable = [
        'site_id',
        'theme_id',
        'sort_no',
        'appraiser_id',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
