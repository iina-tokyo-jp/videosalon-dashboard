<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class AdFreeofferPoints extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ad_freeoffer_points';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'site_id',
        'ad_code',
        'target_at',
        'promotion_code',
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
        'target_at',
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
