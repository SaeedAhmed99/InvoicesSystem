<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesDetials extends Model
{
    use HasFactory;
    protected $table = 'invoices_detials';
    protected $fillable = [
        'invoice_number',
        'product',
        'Section',
        'Status',
        'Value_Status',
        'note',
        'user',
        'payment_date',
        'invoice_id'
    ];
}
