<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $table = 'payroll';
    protected $fillable = [
        'client_id',
        'payroll_start',
        'payroll_end',
        'time_sheet_file',
        'payroll_file',
        'status',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
