<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Meu Perfil</h4>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h5 class="mb-4">Dados da ONG</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Nome</th>
                                        <td>{{ $ong->nome }}</td>
                                    </tr>
                                    <tr>
                                        <th>CNPJ</th>
                                        <td>{{ $ong->cnpj }}</td>
                                    </tr>
                                    <tr>
                                        <th>E-mail</th>
                                        <td>{{ $ong->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telefone</th>
                                        <td>{{ $ong->telefone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Endereço</th>
                                        <td>{{ $ong->endereco }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('perfil.editar') }}" class="btn btn-warning">
                                Editar Perfil
                            </a>
                            <a href="/" class="btn btn-secondary">
                                Página Inicial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>