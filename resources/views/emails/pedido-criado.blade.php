<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            color: #333;
        }
        .email-container {
            background: #ffffff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .button {
            background-color: #38bdf8;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Seu pedido foi registrado com sucesso!</h2>

        <p><strong>Código do pedido:</strong> {{ $pedido->codigo }}</p>

        <p>Você pode acompanhar o andamento do seu pedido a qualquer momento clicando no botão abaixo:</p>

        <a href="{{ route('pedido.visualizar-externo', $pedido->codigo) }}" class="button">
            Acompanhar Pedido
        </a>

        <p style="margin-top: 30px;">Se você não solicitou essa doação, por favor ignore este e-mail.</p>

        <div class="footer">
            Este é um e-mail automático enviado por ONGhub.
        </div>
    </div>
</body>
</html>
