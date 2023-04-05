<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'payment_date',
        'payment_method',
        'total_paid',
        'remarks',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

}
