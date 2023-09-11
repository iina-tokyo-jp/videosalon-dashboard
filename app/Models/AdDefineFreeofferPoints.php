<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class AdDefineFreeofferPoints extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ad_define_freeoffer_points';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'site_id',
        'promotion_code',
        'name',
        'reason',
        'status',
        'begin_at',
        'end_at',
        'point',
        'point_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*
     protected $hidden = [
        'login_pw'
    ];
    */

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /*
    public function info() {
        return $this->hasOne(Userinfo::class, 'id');
    }
    */

    public $timestamps = false;
}
