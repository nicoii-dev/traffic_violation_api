<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseInfo;
use App\Models\Vehicle;
use App\Models\CitationInfo;
use App\Models\ViolationList;

class Violator extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'municipality',
        'zipcode',
        'barangay',
        'street',
        'nationality',
        'phone_number',
        'dob',
    ];

    public function license() {
        return $this->hasOne(LicenseInfo::class, 'violator_id', 'id');
    }

    public function vehicle() {
        return $this->hasMany(Vehicle::class, 'violator_id', 'id');
    }

    // public function citation() {
    //     return $this->hasOne(CitationInfo::class, 'violator_id', 'id');
    // }

    // public function violations()
    // {
    //     return $this->hasMany(ViolationList::class, 'id', array('violations'));
    // }
    
}
