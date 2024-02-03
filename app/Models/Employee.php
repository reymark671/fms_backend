<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'file_dir',
        'client_id',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
