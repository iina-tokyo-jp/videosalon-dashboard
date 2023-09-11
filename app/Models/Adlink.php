<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class Adlink extends Model
{
    use OptimisticLockTrait;

    protected $table = 'adlinks';

    protected $dates = [
        'add_date',
        'mod_date'
    ];

    public $timestamps = false;
}