<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de ONG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Cadastro de ONG</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ong.register') }}">
                            @csrf

                            <!-- Dados da ONG -->
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome da ONG*</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>

                            <div class="mb-3">
                                <label for="cnpj" class="form-label">CNPJ*</label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail*</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone*</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" required>
                            </div>

                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço Completo*</label>
                                <textarea class="form-control" id="endereco" name="endereco" rows="2" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Senha*</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirme a Senha*</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cadastrar ONG</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>