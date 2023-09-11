<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enable',
        'name',
        'start_at',
        'last_join_at',
        'uu_count',
        'total_sales',
        'average_sales',
        'total_cost',
        'balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = [
        'start_at',
        'last_join_at'
    ];

    public function info() {
        return $this->hasOne(Userinfo::class, 'id');
    }
}
