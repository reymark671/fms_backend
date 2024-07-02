<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFileUpload extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'report_file',
        'client_id',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
