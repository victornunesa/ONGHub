<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Editar Perfil</h4>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('perfil.atualizar') }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nome" class="form-label">Nome da ONG*</label>
                                    <input type="text" class="form-control" id="nome" name="nome" 
                                        value="{{ old('nome', $ong->nome) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cnpj" class="form-label">CNPJ*</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                        value="{{ old('cnpj', $ong->cnpj) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail*</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        value="{{ old('email', $ong->email) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="telefone" class="form-label">Telefone*</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" 
                                        value="{{ old('telefone', $ong->telefone) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço Completo*</label>
                                <textarea class="form-control" id="endereco" name="endereco" 
                                    rows="3" required>{{ old('endereco', $ong->endereco) }}</textarea>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Alterar Senha</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="current_password" class="form-label">Senha Atual</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                    <div class="form-text text-muted">Preencha apenas se quiser alterar a senha</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="new_password" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="col-md-6">
                                    <label for="new_password_confirmation" class="form-label">Confirme a Nova Senha</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    A senha deve conter: mínimo 8 caracteres, letras maiúsculas e minúsculas, números e símbolos.
                                </small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('perfil') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#cnpj').mask('00.000.000/0000-00');
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</body>
</html>