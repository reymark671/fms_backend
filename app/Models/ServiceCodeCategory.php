<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCodeCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_description',
    ];
    public function serviceCodes()
    {
        return $this->hasMany(ServiceCode::class, 'service_code_category_id');
    }
}
