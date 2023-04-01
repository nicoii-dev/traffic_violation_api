<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityServiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'discount',
        'time_to_render'
    ];
}
