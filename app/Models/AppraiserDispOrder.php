<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppraiserDispOrder extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'appraiser_id',
        'disp_order_id',
        'disp_order_num'
    ];

    protected $dates = [
        'mod_date'
    ];

    public $timestamps = false;

    public function appraiserinfo()
    {
        $newResource = clone $this;
        return $newResource->setConnection('pgsql')->belongsTo(Appraiser::class, 'appraiser_id')->withDefault();
    }

    public function user()
    {
        $newResource = clone $this;
        return $newResource->setConnection('pgsql')->belongsTo(UserAppraiser::class, 'appraiser_id', 'appraiser_id');
    }
}
