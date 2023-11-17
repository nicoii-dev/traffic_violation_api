<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Violator;
class LicenseInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'violator_id',
        'license_number',
        'license_type',
        'license_status',
    ];
    
    public function violator()
    {
        return $this->belongsTo(Violator::class, 'violator_id', 'id');
    }
}
