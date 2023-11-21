<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitationInfo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'citation_id',
        'date',
        'violations',
        'sub_total',
        'discount',
        'total_amount',
        'status',
        'expired'
    ];

    public function citation()
    {
        return $this->belongsTo(CitationInfo::class, 'citation_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_categories_id', 'id');
    }
}
