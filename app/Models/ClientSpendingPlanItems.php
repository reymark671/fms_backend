<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSpendingPlanItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_spending_plan_id',
        'service_code_id',
        'allocated_budget',
    ];

    public function spendingPlan()
    {
        return $this->belongsTo(ClientSpendingPlan::class);
    }

    public function serviceCode()
    {
        return $this->belongsTo(ServiceCode::class);
    }
}
