<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ResumenCobros extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $proximas,
        public Collection $tolerancia,
        public Collection $vencidas,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resumen semanal de cobros — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.resumen_cobros',
        );
    }
}
