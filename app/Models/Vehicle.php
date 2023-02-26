<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'violator_id',
        'plate_number',
        'make',
        'model',
        'color',
        'class',
        'body_markings',
        'registered_owner',
        'owner_address',
        'vehicle_status',
    ];

    public function violator()
    {
        return $this->belongsTo(Violator::class, 'violator_id', 'id');
    }
}
