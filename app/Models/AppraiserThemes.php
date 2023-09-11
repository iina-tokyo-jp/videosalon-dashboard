<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppraiserThemes extends Model
{
    protected $table = 'appraiser_themes';

    protected $fillable = [
        'site_id',
        'title',
        'description',
        'add_date',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
