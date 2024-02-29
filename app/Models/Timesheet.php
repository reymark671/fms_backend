<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'total_hours',
        'specification',
        'status',
        'client_id',
        'employee_id',
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
