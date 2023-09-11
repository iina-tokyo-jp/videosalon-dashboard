<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class AppraiserAggregates extends Model
{
    use OptimisticLockTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'appraiser_aggregates';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'site_id',
        'appraiser_id',
        'appraiser_name',
        'target_at',
        'stb_time',
        'rest_time',
        'appraisal_count',
        'rightnow_count',
        'reserve_count',
        'active_count',
        'video_time',
        'video_avg_time',
        'sound_time',
        'sound_avg_time',
        'error_count',
        'review_count',
        'blog_count',
        'sales_wtax',
        'sales_wotax',
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
