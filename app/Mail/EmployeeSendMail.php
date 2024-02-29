<?php
// EmployeeSendMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class EmployeeSendMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderEmail;
    public string $subject_data;
    public string $message_data;
    public string $full_name;

    /**
     * Create a new message_data instance.
     */
    public function __construct(string $senderEmail, string $subject_data, string $message_data, string $full_name=null)
    {
        $this->senderEmail = $senderEmail;
        $this->subject_data = $subject_data;
        $this->message_data = $message_data;
        $this->full_name = $full_name;
    }

    /**
     * Get the message_data envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject_data,
            from: $this->senderEmail
        );
    }

    /**
     * Build the message_data.
     */
    public function build()
    {
        return $this->subject($this->subject_data)
                    ->view('emails.employee_send', 
                    [
                        'message_data' => $this->message_data,
                        'subject_data' => $this->subject_data,
                        'full_name'    => $this->full_name,
                ]);
    }
}
