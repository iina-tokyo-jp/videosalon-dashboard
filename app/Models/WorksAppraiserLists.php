<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorksAppraiserLists extends Model
{
    protected $table = 'works_appraiser_lists';

    protected $fillable = [
        'site_id',
        'theme_id',
        'sort_no',
        'appraiser_id',
        'begin_date',
        'end_date',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
