<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DefaultEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: @$this->mailData['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: @$this->mailData['view'],
            with: @$this->mailData['with'],
        );
    }

    public function attachments()
    {
        return @$this->mailData['attachments'] ?? [];
    }
}
