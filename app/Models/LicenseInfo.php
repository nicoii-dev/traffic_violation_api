<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'violator_id',
        'license_number',
        'license_type',
        'license_status',
    ];
}
