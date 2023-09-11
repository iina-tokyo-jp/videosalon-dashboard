<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppraiserStandby extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    protected $table = 'appraiser_standby';
    protected $primaryKey = 'appraiser_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appraiser_id',
        'site_id',
        'status',
    ];

    public $timestamps = false;
}
