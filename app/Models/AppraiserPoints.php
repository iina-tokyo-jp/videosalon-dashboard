<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppraiserPoints extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    protected $table = 'appraiser_points';
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'appraiser_id',
        'kind',
        'point_purchase',
        'point_sales',
    ];

    public $timestamps = false;
}
