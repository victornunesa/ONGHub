<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulário de Doação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .item-doacao {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        .remove-item {
            cursor: pointer;
            color: #dc3545;
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">Formulário de Solicitação de Alimentos</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('doacao.store') }}" method="POST" id="formDoacao">
                            @csrf
                            
                            <h5 class="mb-4">Dados Pessoais</h5>
                            <div class="mb-3">
                                <label for="nome_solicitante" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome_solicitante" name="nome_solicitante" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email_solicitante" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_solicitante" name="email_solicitante" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone_solicitante" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="telefone_solicitante" name="telefone_solicitante" required>
                            </div>

                            <hr class="my-4">
                            
                            <h5 class="mb-4">Itens solicitados</h5>
                            <div id="itens-container">
                                <!-- Primeiro item -->
                                <div class="item-doacao">
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Descrição do Alimento</label>
                                            <input type="text" class="form-control" name="itens[0][descricao]" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" name="itens[0][quantidade]" min="1" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Unidade</label>
                                            <select class="form-select" name="itens[0][unidade]" required>
                                                <option value="">Selecione...</option>
                                                <option value="kg">Quilograma (kg)</option>
                                                <option value="g">Grama (g)</option>
                                                <option value="L">Litro (L)</option>
                                                <option value="ml">Mililitro (ml)</option>
                                                <option value="un">Unidade (un)</option>
                                                <option value="cx">Caixa (cx)</option>
                                                <option value="pct">Pacote (pct)</option>
                                                <option value="lata">Lata</option>
                                                <option value="saca">Saco</option>
                                                <option value="dz">Dúzia (dz)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end mb-3">
                                            <span class="remove-item" style="font-size: 1.5rem;">×</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="adicionar-item" class="btn btn-outline-primary mb-4">
                                + Adicionar outro item
                            </button>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg">Confirmar solicitação</button>
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('itens-container');
            const addButton = document.getElementById('adicionar-item');
            let itemCount = 1;

            // Adicionar novo item
            addButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'item-pedidodoacao';
                // No evento de adicionar item:
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" name="itens[${itemCount}][descricao]" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Quantidade</label>
                            <input type="number" step="0.1" class="form-control" name="itens[${itemCount}][quantidade]" min="0.1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Unidade</label>
                            <select class="form-select" name="itens[${itemCount}][unidade]" required>
                                <option value="">Selecione...</option>
                                <option value="kg">Quilograma (kg)</option>
                                <option value="g">Grama (g)</option>
                                <option value="L">Litro (L)</option>
                                <option value="ml">Mililitro (ml)</option>
                                <option value="un">Unidade (un)</option>
                                <option value="cx">Caixa (cx)</option>
                                <option value="pct">Pacote (pct)</option>
                                <option value="lata">Lata</option>
                                <option value="saca">Saco</option>
                                <option value="dz">Dúzia (dz)</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-3">
                            <span class="remove-item">×</span>
                        </div>
                    </div>
                `;
                container.appendChild(newItem);
                itemCount++;
            });

            // Remover item
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    if (document.querySelectorAll('.item-pedidodoacao').length > 1) {
                        e.target.closest('.item-pedidodoacao').remove();
                    } else {
                        alert('Você precisa solicitar pelo menos um item.');
                    }
                }
            });
        });
    </script>
</body>
</html>