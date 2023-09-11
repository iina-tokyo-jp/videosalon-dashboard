<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'role_id',
        'login_id',
        'login_pw',
        'add_date',
        'mod_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'login_pw'
    ];

    protected $dates = [
        'add_date',
        'mod_date'
    ];

    public function info() {
        return $this->hasOne(Userinfo::class, 'id');
    }

    public $timestamps = false;
}
