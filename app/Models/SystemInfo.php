<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class SystemInfo extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    protected $table = 'system_info';

    protected $fillable = [
        'site_id',
        'is_weeklyrank_work',
        'is_weeklyrank',
        'is_monthlyrank_work',
        'is_monthlyrank',
        'is_recommendedrank_work',
        'is_recommendedrank',
        'dayofweek',
        'mod_date'
    ];

    protected $dates = ['mod_date'];

    public $timestamps = false;
}
