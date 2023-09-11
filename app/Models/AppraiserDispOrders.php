<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppraiserDispOrders extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    protected $table = 'appraiser_disp_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'appraiser_id',
        'disp_order_id',
        'disp_order_num',
    ];

    public $timestamps = false;
}
