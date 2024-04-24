<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_name',
        'mobile',
        'phone',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'username',
        'password',
        'otp',
        'is_active'
    ];
}
