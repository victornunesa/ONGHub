<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido {{ $pedidos->first()->codigo }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 700px; margin: auto; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #eee; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pedido {{ $pedidos->first()->codigo }}</h1>
        <p><strong>Solicitante:</strong> {{ $pedidos->first()->nome_solicitante }}</p>
        <p><strong>Data:</strong> {{ $pedidos->first()->data_pedido }}</p>

        <h3>Itens do Pedido:</h3>
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>{{ $item->unidade }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
