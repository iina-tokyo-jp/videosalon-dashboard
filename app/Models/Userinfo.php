<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class Userinfo extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $dates = ['last_login_date', 'regphone_date', 'add_date', 'mod_date'];

    public $timestamps = false;


}
