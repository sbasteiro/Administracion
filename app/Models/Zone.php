<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    /**
     * @var false|mixed|string
     */

    protected $table = 'zone';
    protected $fillable =  [
        'id_zone',
        'name',
        'points',
        'created_at',
        'update_at',
    ];
}
