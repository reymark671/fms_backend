<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'region_center',
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
    public function assignments()
    {
        return $this->hasMany(CoordinatorAssignment::class, 'coordinator_id');
    }
}
