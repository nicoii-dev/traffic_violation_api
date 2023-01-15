<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationList extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'violation_name',
        'penalty',
        'violation_categories_id',
    ];
}
