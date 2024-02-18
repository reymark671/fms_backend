<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_dir',
        'client_id',
        'employee_id',
        'description',
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
