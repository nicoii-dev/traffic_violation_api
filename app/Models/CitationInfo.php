<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Violator;

class CitationInfo extends Model
{
    use HasFactory;

    public function violator()
    {
        return $this->hasOne(Violator::class, 'violator_id', 'id');
    }
}
