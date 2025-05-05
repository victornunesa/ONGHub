<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Reativar Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Voltar para Início</a>
        </div>

        <h2 class="mb-4">Reativar Conta</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reativar.conta.processar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">E-mail da Conta</label>
                <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Reativar Conta</button>
        </form>
    </div>
</body>
</html>
