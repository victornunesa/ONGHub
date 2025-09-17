<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub - Doar Alimentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center relative" 
      style="background-image: url('https://source.unsplash.com/1600x900/?food,donation');">

    
      <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/donation-pattern.png') }}');">
         
        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>
    

    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-4xl bg-white/95 backdrop-blur-md rounded-2xl shadow-xl p-8 ">
        
            <!-- Logo/ícone -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg text-white">
                    <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    <!-- <img src="/images/food-delivery.png" alt="Ícone ONGHub" class="w-10 h-10 object-contain"> -->
                </div>
            </div>

            <h2 class="text-3xl font-medium font-bold text-center mb-6 text-amber-600 animate-pulse">
                Doação de Alimentos
            </h2>
            <style>
            @keyframes typing {
            from { width: 0 }
            to { width: 100% }
            }

            .animate-typing {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            animation: typing 4s steps(40, end);
            }
            </style>

            <!-- Mensagens de erro -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('intencao.store') }}" method="POST" id="formDoacao" class="space-y-6">
                @csrf

                <!-- Dados pessoais -->
                <div class="grid md:grid-cols-3 gap-4">
                    <input type="text" name="nome_solicitante" placeholder="Nome*" 
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>

                    <input type="email" name="email_solicitante" placeholder="E-mail*" 
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>

                    <input type="tel" name="telefone_solicitante" placeholder="Telefone*" 
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>
                </div>

                <!-- Seleção ONG -->
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Selecione a ONG</label>
                    <select name="ong_desejada" id="ong_desejada" required
                            class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none">
                        <option value="">Selecione uma ONG</option>
                        @foreach($ongs as $ong)
                            <option value="{{ $ong->id }}">{{ $ong->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Itens -->
                <div id="itens-container" class="space-y-4">
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl relative">
                        <div class="grid md:grid-cols-3 gap-4">
                            <input type="text" name="itens[0][descricao]" placeholder="Descrição do alimento*" 
                                   class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>

                            <input type="number" name="itens[0][quantidade]" placeholder="Quantidade*" min="1" 
                                   class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>

                            <select name="itens[0][unidade]" required
                                    class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none">
                                <option value="">Unidade...</option>
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
                        <button type="button" class="absolute top-2 right-2 text-red-500 font-bold remove-item">×</button>
                    </div>
                </div>

                <!-- Botão adicionar item -->
                <button type="button" id="adicionar-item" 
                        class="w-full py-2 px-4 rounded-xl border border-amber-400 text-amber-600 font-medium hover:bg-amber-50 hover:scale-105 transition">
                    + Adicionar outro item
                </button>

                <!-- Botões finais -->
                <div class="grid md:grid-cols-2 gap-4">
                    <button type="submit" 
                            class="w-full py-2 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 text-white font-semibold shadow-md hover:scale-105 transition">
                        Registrar Doação
                    </button>
                    <a href="{{ url('/') }}" class="w-full py-2 rounded-xl border-gray-200 text-black font-medium text-center transition bg-gray-200 hover:bg-red-500 hover:scale-105 hover:text-white shadow-lg hover:shadow-xl">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('itens-container');
            const addButton = document.getElementById('adicionar-item');
            let itemCount = 1;

            addButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'p-4 bg-gray-50 border border-gray-200 rounded-xl relative';
                newItem.innerHTML = `
                    <div class="grid md:grid-cols-3 gap-4">
                        <input type="text" name="itens[${itemCount}][descricao]" placeholder="Descrição do alimento*" 
                               class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>
                        <input type="number" name="itens[${itemCount}][quantidade]" placeholder="Quantidade*" min="1" 
                               class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none" required>
                        <select name="itens[${itemCount}][unidade]" required
                                class="w-full p-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 outline-none">
                            <option value="">Unidade...</option>
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
                    <button type="button" class="absolute top-2 right-2 text-red-500 font-bold remove-item">×</button>
                `;
                container.appendChild(newItem);
                itemCount++;
            });

            // Remover item
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    if (document.querySelectorAll('#itens-container > div').length > 1) {
                        e.target.closest('div').remove();
                    } else {
                        alert('Você precisa ter pelo menos um item de doação.');
                    }
                }
            });
        });
    </script>
</body>
</html>
