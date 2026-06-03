<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class enviarEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdf;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $pdf = null)
    {
        $this->data = $data;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['titulo'] ?? $this->data['asunto'] ?? 'Notificación Check 360',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->data['vista'],
            with: $this->data,
        );
    }

    public function attachments(): array
    {
        $attachments = [];
    
        if ($this->pdf) {
            $attachments[] = Attachment::fromData(
                fn () => $this->pdf,
                'evaluacion.pdf'
            )->withMime('application/pdf');
        }
    
        return $attachments;
    }
}
