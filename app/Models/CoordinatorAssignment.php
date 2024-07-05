<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatorAssignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'coordinator_id',
        'client_id',
        'status',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class,'coordinator_id');
    }
}
