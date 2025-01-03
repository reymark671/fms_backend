<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorEnrolledAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id',
        'client_id',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
