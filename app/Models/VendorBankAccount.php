<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBankAccount extends Model
{
    use HasFactory;
    protected $table = 'vendor_bank_account';
    protected $fillable = [
        'vendor_id',
        'account_type',
        'bank_name',
        'routing_number',
        'account_number',
        'paystub_copy'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
