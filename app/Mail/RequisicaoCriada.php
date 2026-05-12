<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisicaoCriada extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Requisicao  $requisicao  Deve incluir relações `user` e `livro` (e opcionalmente `livro.autores`).
     */
    public function __construct(
        public Requisicao $requisicao,
        public bool $forAdmin = false,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $ref = str_pad((string) $this->requisicao->id, 5, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: $this->forAdmin
                ? "Nova requisição #{$ref} — ".config('app.name')
                : "Confirmação da sua requisição #{$ref} — ".config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisicao.criada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
