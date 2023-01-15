<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\CommunityService;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'discount',
        'received_date',
        'payment_method',
        'total_amount',
        'total_paid',
        'remarks',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

}
