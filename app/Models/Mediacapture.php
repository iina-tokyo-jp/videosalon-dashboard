<?php

namespace App\Models;

use App\Traits\OptimisticLockTrait;
use Illuminate\Database\Eloquent\Model;

class Mediacapture extends Model
{
    use OptimisticLockTrait;

    protected $connection = 'pgsql2';
    protected $table = 'video_mediacapturepipelines';

    protected $fillable = [
        'id',
        'site_id',
        'meeting_id',
        'video_id',
        'mediacapturepipeline',
        'status',
        'begin_date',
        'mod_date'
    ];

    protected $dates = [
        'begin_date',
        'mod_date'
    ];

    public $timestamps = false;
}