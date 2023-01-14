<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseInfo;
use App\Models\Vehicle;

class Violator extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'address',
        'nationality',
        'phone_number',
        'dob',
    ];

    public function license() {
        return $this->hasOne(LicenseInfo::class, 'violator_id', 'id');
    }

    public function vehicle() {
        return $this->hasOne(Vehicle::class, 'violator_id', 'id');
    }
}
