<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientSendMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $senderEmail;
    public string $subject_data;
    public string $message_data;
    public string $full_name;

    /**
     * Create a new message instance.
     */
    public function __construct(string $senderEmail, string $subject_data, string $message_data, string $full_name=null)
    {
        $this->senderEmail = $senderEmail;
        $this->subject_data = $subject_data;
        $this->message_data = $message_data;
        $this->full_name = $full_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject_data,
            from: $this->senderEmail
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject($this->subject_data)
                    ->view('emails.client_send', 
                    [
                        'message_data' => $this->message_data,
                        'subject_data' => $this->subject_data,
                        'full_name'    => $this->full_name,
                ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
