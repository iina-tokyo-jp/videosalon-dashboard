<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Datalog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id',
        'actor_kind',
        'user_id',
        'user_name',
'user_account',     // edited by ohneta
'by_api',           // edited by ohneta
'kind',             // edited by ohneta
        'title',
//        'description',
        'detail',   // edited by ohneta
        'gp4app',
        'add_date',
    ];

    protected $dates = [
        'add_date'
    ];

    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
