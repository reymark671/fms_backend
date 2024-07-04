<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiredEmployees extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'employee_id',
        'position',
        'hired_date',
        'separation_date',
        'status',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
