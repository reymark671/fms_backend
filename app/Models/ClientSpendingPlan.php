<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSpendingPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_code',
        'total_budget',
        'client_id',
        'from',
        'to'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(ClientSpendingPlanItems::class);
    }
}
