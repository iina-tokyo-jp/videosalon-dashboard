<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriendsite extends Model
{

    protected $table = 'user_friendsites';

    protected $fillable = [
        'site_id',
        'user_id',
        'fsite_id',
        'fuser_id',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
