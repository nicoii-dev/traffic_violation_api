<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitationInfo;

class CommunityService extends Model
{
    use HasFactory;

    public function community()
    {
        return $this->hasOne(CitationInfo::class, 'citation_id', 'id');
    }
}
