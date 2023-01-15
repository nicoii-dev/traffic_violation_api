<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ViolationList;

class CitationInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'violator_id',
        'user_id',
        'violations',
        'date_of_violation',
        'time_of_violation',
        'municipality',
        'zipcode',
        'barangay',
        'street',
    ];

    // public function violations()
    // {
    //     return $this->hasMany(ViolationList::class, 'id', array('violations'));
    // }
}
