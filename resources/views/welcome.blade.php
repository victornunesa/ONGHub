<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container text-center mt-5">
        <h1 class="mb-4">Bem-vindo ao ONGHub</h1>
        <p class="lead mb-5">Acesse ou cadastre sua ONG para começar.</p>

        <div class="d-grid gap-3 col-6 mx-auto">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
            <a href="{{ route('user.registration') }}" class="btn btn-success btn-lg">Cadastrar ONG</a>
            <a href="{{ route('reativar.conta') }}" class="btn btn-outline-secondary btn-lg">Reativar Conta</a>
        </div>
    </div>

</body>
</html>

