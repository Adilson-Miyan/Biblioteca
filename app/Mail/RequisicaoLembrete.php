<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisicaoLembrete extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Requisicao  $requisicao  Deve incluir `user` e `livro`.
     */
    public function __construct(public Requisicao $requisicao) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $ref = str_pad((string) $this->requisicao->id, 5, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: "Lembrete de devolução #{$ref} — ".config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisicao.lembrete',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
