<?php

namespace App\Mail;

use App\Models\PedidoDoacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoCriadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public PedidoDoacao $pedido;

    public function __construct(PedidoDoacao $pedido)
    {
        $this->pedido = $pedido;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Pedido Criado',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-criado',
            with: [
                'pedido' => $this->pedido,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

