<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{

    use SoftDeletes;

    /**
     * @var false|mixed|string
     */

    protected $table = 'shipping';
    protected $fillable =  [
        'id_shipping',
        'buyer_name',
        'description',
        'photo_url',
        'address',
        'zone_id',
        'longitude',
        'latitude',
        'status',
        'created_at',
        'update_at',
        'deleted_at'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
