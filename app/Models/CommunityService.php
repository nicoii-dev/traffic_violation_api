<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitationInfo;

class CommunityService extends Model
{
    use HasFactory;

    protected $fillable = [
        'violator_id',
        'community_service_details_id',
        'rendered_time',
        'status'
    ];

    public function violator()
    {
        return $this->belongsTo(Violator::class, 'violator_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(CommunityServiceDetails::class, 'community_service_details_id', 'id');
    }
}
