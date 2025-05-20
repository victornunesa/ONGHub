<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulário de Intenção de Doação</title>
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
        .ong-card {
            cursor: pointer;
            transition: all 0.3s;
        }
        .ong-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .ong-card.selected {
            border: 2px solid #ffc107;
            background-color: #fff8e1;
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
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">Formulário de Intenção de Doação de Alimentos</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('intencao.store') }}" method="POST" id="formDoacao">
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
                            
                            <h5 class="mb-4">Selecione a ONG para doação</h5>
                            <div class="row mb-4" id="ongs-container">
                                @foreach($ongs as $ong)
                                <div class="col-md-6 mb-3">
                                    <div class="card ong-card" data-ong-id="{{ $ong->id }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $ong->nome }}</h5>
                                            <p class="card-text">
                                                <strong>CNPJ:</strong> {{ $ong->cnpj }}<br>
                                                <strong>Email:</strong> {{ $ong->email }}<br>
                                                <strong>Telefone:</strong> {{ $ong->telefone }}<br>
                                                <strong>Endereço:</strong> {{ $ong->endereco }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="ong_desejada" id="ong_desejada" required>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-4">Itens a serem doados</h5>
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
                                <button type="submit" class="btn btn-warning btn-lg">Registrar intenção de doação</button>
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
            const ongCards = document.querySelectorAll('.ong-card');
            const ongDesejadaInput = document.getElementById('ong_desejada');
            let itemCount = 1;

            // Seleção de ONG
            ongCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove a seleção de todas as ONGs
                    ongCards.forEach(c => c.classList.remove('selected'));
                    
                    // Adiciona seleção à ONG clicada
                    this.classList.add('selected');
                    
                    // Define o valor do input hidden
                    ongDesejadaInput.value = this.dataset.ongId;
                });
            });

            // Validação do formulário - verifica se uma ONG foi selecionada
            document.getElementById('formDoacao').addEventListener('submit', function(e) {
                if (!ongDesejadaInput.value) {
                    e.preventDefault();
                    alert('Por favor, selecione uma ONG para a doação.');
                    return false;
                }
            });

            // Adicionar novo item
            addButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'item-doacao';
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
                    if (document.querySelectorAll('.item-doacao').length > 1) {
                        e.target.closest('.item-doacao').remove();
                    } else {
                        alert('Você precisa ter pelo menos um item de doação.');
                    }
                }
            });
        });
    </script>
</body>
</html>