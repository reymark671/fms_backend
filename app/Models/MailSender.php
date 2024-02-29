<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSender extends Model
{
    use HasFactory;
    protected $table = 'mail_sender';
    protected $fillable = [
        'subject',
        'message',
        'recipient',
        'sender',
    ];
}
