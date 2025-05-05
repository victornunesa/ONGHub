<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao Sistema de ONGs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5 text-center">
        <h1 class="mb-4">Bem-vindo ao Sistema de Gerenciamento de ONGs</h1>

        <p class="lead mb-5">Gerencie sua ONG, edite seus dados e mantenha suas informações organizadas em um só lugar.</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
            <a href="{{ route('user.registration') }}" class="btn btn-success btn-lg">Cadastrar ONG</a>
        </div>

        <div class="mt-4">
            <a href="{{ route('reativar.conta') }}" class="text-muted">Reativar conta inativa</a>
        </div>
    </div>

</body>
</html>
