<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Violator;
use App\Models\User;
use App\Models\LicenseInfo;
use App\Models\Vehicle;

class CitationInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'violator_id',
        'license_id',
        'vehicle_id',
        'violations',
        'date_of_violation',
        'time_of_violation',
        'municipality',
        'zipcode',
        'barangay',
        'street',
    ];

    public function violator() {
        // return $this->hasOne($this, 'violator_id', 'id');
        return $this->belongsTo(Violator::class, 'violator_id', 'id');
    }
    
    public function enforcer() {
        // return $this->hasMany($this, 'violator_id', 'id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function license() {
        // return $this->hasOne($this, 'violator_id', 'id');
        return $this->belongsTo(LicenseInfo::class, 'license_id', 'id');
    }

    public function vehicle() {
        // return $this->hasOne($this, 'violator_id', 'id');
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
