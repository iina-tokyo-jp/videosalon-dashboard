<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class AdAggregates extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ad_aggregates';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'site_id',
        'ad_code',
        'target_at',
        'pv_count',
        'signup_count',
        'noncharge_count',
        'charge_count',
        'total_sales',
        'total_adcosts',
        'total_freepoints',
        'gross_profit_wtax',
        'gross_profit_wotax',
        'return_rate',
        'prepaied_card',
        'prepaied_bank',
        'prepaied_other',
        'postpaied_card',
        'postpaied_bank',
        'postpaied_other',
        'eachpaied_card',
        'nonpaied',
        'nonpaied_rate',
        'comment'
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
