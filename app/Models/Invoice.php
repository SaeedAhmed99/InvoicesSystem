<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products;
use App\Models\Section;
use App\Models\InvoicesDetials;
use App\Models\InvoiceAttachments;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'section_id',
        'product_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Value_VAT',
        'Rate_VAT',
        'Total',
        'Status',
        'Value_Status',
        'note',
        'Payment_Date',
    ];

    protected $dates = ['deleted_at'];

    public function section(){
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function details(){
        return $this->hasOne(InvoicesDetials::class);
    }

    public function attachment(){
        return $this->hasMany(InvoiceAttachments::class);
    }
}
