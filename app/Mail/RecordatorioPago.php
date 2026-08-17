<?php

namespace App\Mail;

use App\Models\Suscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioPago extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Suscripcion $suscripcion)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu pago de internet vence pronto — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio',
        );
    }
}
