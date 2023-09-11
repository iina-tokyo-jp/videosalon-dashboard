<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLine extends Model
{

    protected $table = 'user_lines';

    protected $fillable = [
        'site_id',
        'user_id',
        'appraiser_id',
        'line_id',
        'rel_code',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
