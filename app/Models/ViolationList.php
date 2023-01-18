<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ViolationCategory;

class ViolationList extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_categories_id',
        'violation_name',
        'penalty',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_categories_id', 'id');
    }
}
