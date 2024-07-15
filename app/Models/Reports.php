<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reports extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'report_type',
        'description',
        'report_date',
        'report_file',
        'uploaded_by',
        'report_destination_type',
        'report_destination_account_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function destinationAccounts()
    {
        $accountIds = explode(',', $this->report_destination_account_id);
        switch ($this->report_destination_type) {
            case 1:
                return Coordinator::whereIn('id', $accountIds)->get();
            case 2:
                return Employee::whereIn('id', $accountIds)->get();
            case 3:
                return Client::whereIn('id', $accountIds)->get();
            default:
                return collect(); // Return an empty collection if type is not valid
        }
    }

    public function getDestinationAccountFullNamesAttribute()
    {
        $accounts = $this->destinationAccounts();
        return $accounts->map(function ($account) {
            return $account->first_name . ' ' . $account->last_name;
        })->implode(', ');
    }
}
