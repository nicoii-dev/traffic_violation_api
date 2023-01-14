<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitationInfo;

class PaymentRecord extends Model
{
    use HasFactory;

    public function citation()
    {
        return $this->hasOne(CitationInfo::class, 'citation_id', 'id');
    }
}
