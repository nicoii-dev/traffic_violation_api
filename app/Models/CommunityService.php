<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitationInfo;
use App\Models\Violator;
use App\Models\Invoice;

class CommunityService extends Model
{
    use HasFactory;

    protected $fillable = [
        'citation_id',
        'invoice_id',
        'community_service_details_id',
        'rendered_time',
        'status'
    ];

    public function citation()
    {
        return $this->belongsTo(CitationInfo::class, 'citation_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(CommunityServiceDetails::class, 'community_service_details_id', 'id');
    }
}
