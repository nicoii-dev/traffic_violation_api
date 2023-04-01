<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Violator;

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
        'status'
    ];

    public function violator()
    {
        return $this->belongsTo(Violator::class, 'citation_id', 'id');
    }
}
