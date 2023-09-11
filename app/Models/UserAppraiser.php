<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UserAppraiser extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql';
    protected $table = 'user_appraisers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'user_id',
        'appraiser_id',
//        'mod_date',
    ];

    protected $dates = [
        'mod_date'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function info()
    {
        return $this->belongsTo(Userinfo::class, 'user_id', 'user_id');
    }

    public function appraiser()
    {
        return $this->belongsTo(Appraiser::class, 'appraiser_id')->withDefault();
    }
}
