<?php

namespace App\Mail;

use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReciboPago extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Recibo $recibo)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo de pago '.$this->recibo->folio.' — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recibo',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('recibos.pdf', ['recibo' => $this->recibo]);

        return [
            Attachment::fromData(fn () => $pdf->output(), $this->recibo->folio.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
