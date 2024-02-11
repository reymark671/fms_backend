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
}
