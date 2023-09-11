<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appraiser extends Model
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
        'status',
        'name',
        'profile1',
        'profile2',
        'pref_no',
        'image',
        'types',
    ];

    protected $dates = [
        'add_date',
        'mod_date'
    ];

    public $timestamps = false;

    public function user()
    {
        $newResource = clone $this;
        return $newResource->setConnection('pgsql')->belongsTo(UserAppraiser::class, 'id', 'appraiser_id');
    }
}
