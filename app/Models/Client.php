<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'ss_number',
        'address',
        'city',
        'state',
        'zip_code',
        'contact_number',
        'email',
        'password',
        'status',
        'api_token',
        'otp'
    ];
    public function spendingPlans()
    {
        return $this->hasMany(ClientSpendingPlan::class);
    }

    public function files_upload()
    {
        return $this->hasMany(ClientFileUpload::class);
    }
}
