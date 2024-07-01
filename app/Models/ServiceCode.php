<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_code_category_id',
        'service_code_description',
        'code',
    ];
    public function category()
    {
        return $this->belongsTo(ServiceCodeCategory::class, 'service_code_category_id');
    }
}
