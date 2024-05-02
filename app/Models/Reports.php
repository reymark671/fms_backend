<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reports extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'report_type',
        'description',
        'report_date',
        'report_file',
        'uploaded_by',
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
