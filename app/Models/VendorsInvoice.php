<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorsInvoice extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'description',
        'vendor_id',
        'date_purchased',
        'client_name',
        'invoice_price',
        'invoice_file',
        'is_complete'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
