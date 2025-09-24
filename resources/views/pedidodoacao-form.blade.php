<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub - Solicitar Alimentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-gradient {
            background: linear-gradient(to right, #f43f5e, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative">

    <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat">

        <div class="absolute inset-0 bg-white/70 backdrop-blur-sm"></div>

        <div class="absolute inset-0 bg-[url('{{ asset('images/donation-pattern.png') }}')] bg-cover bg-center"></div>

        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>


    <!-- Container do formulário -->
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg p-6 md:p-10 relative z-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg text-white">
                <svg class="w-10 h-10 text-white group-hover:-rotate-45 transition-transform animate-pulse" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                    <g stroke-linecap="round" stroke-linejoin="round" stroke= "currentColor" stroke-width="2" transform="translate(1.4066 1.4066) scale(2.81 2.81)">
                        <path d="M51.456 47.291c-0.256 0-0.512-0.098-0.707-0.293L29.812 26.061c-5.952-5.952-5.952-15.637 0-21.589C32.694 1.588 36.528 0 40.605 0S48.517 1.588 51.4 4.472l0.056 0.056 0.056-0.056C54.395 1.588 58.229 0 62.307 0c4.077 0 7.91 1.588 10.794 4.472l0 0 0 0c5.952 5.952 5.952 15.637 0 21.589L52.163 46.998C51.968 47.193 51.712 47.291 51.456 47.291zM40.605 2c-3.543 0-6.875 1.38-9.38 3.886-5.172 5.172-5.172 13.588 0 18.761l20.23 20.23 20.23-20.23c5.172-5.173 5.172-13.589 0-18.761l0 0C69.181 3.38 65.85 2 62.307 2c-3.544 0-6.875 1.38-9.381 3.886l-0.763 0.763c-0.391 0.391-1.023 0.391-1.414 0l-0.763-0.763C47.48 3.38 44.149 2 40.605 2z"/>
                        <path d="M43.036 90c-2.937 0-5.844-1.081-8.666-2.129-3.111-1.156-6.323-2.35-9.521-2.068l-7.79 0.691V56.157l4.222-0.375c2.65-0.231 4.867 0.798 7.011 1.797 2.025 0.943 3.941 1.844 6.142 1.654l14.064-2.552c5.025-0.854 7.791 2.064 9.468 4.721l15.39-7.154c5.769-2.59 12.243 0.01 16.131 6.464 1.011 1.678 0.448 3.906-1.253 4.968-1.993 1.243-3.979 2.487-5.943 3.719C65.158 80.133 50.363 89.403 44.041 89.956 43.706 89.986 43.371 90 43.036 90zM25.834 83.76c3.214 0 6.268 1.135 9.232 2.236 3.07 1.142 5.97 2.218 8.799 1.968 5.843-0.511 21.154-10.104 37.363-20.261 1.966-1.231 3.952-2.477 5.946-3.721 0.78-0.486 1.049-1.491 0.599-2.239-3.341-5.544-8.803-7.828-13.586-5.676L57.16 63.982l-0.456-0.796c-1.52-2.648-3.639-5.256-7.859-4.535l-14.151 2.563c-2.825 0.233-5.074-0.812-7.246-1.823-1.992-0.929-3.879-1.808-5.989-1.617l-2.399 0.213v26.321l5.613-0.498C25.062 83.776 25.449 83.76 25.834 83.76z"/>
                        <path d="M39.091 75.237c-0.467 0-0.885-0.328-0.979-0.804-0.108-0.542 0.243-1.068 0.785-1.177 5.57-1.113 11.833-3.661 19.122-7.779l-1.314-2.291c-0.275-0.479-0.109-1.091 0.369-1.365 0.479-0.273 1.091-0.108 1.365 0.369l1.813 3.161c0.274 0.479 0.109 1.09-0.368 1.364-7.853 4.521-14.589 7.302-20.596 8.502C39.222 75.231 39.156 75.237 39.091 75.237z"/>
                        <path d="M15.54 90H3.528c-1.941 0-3.52-1.579-3.52-3.52V54.192c0-1.94 1.579-3.52 3.52-3.52H15.54c1.941 0 3.52 1.579 3.52 3.52V86.48C19.059 88.421 17.48 90 15.54 90zM3.528 52.673c-0.838 0-1.52 0.682-1.52 1.52V86.48c0 0.838 0.682 1.52 1.52 1.52H15.54c0.838 0 1.52-0.682 1.52-1.52V54.192c0-0.838-0.682-1.52-1.52-1.52H3.528z"/>
                    </g>
                </svg>
                <!-- <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg> -->
            </div>
        </div>

        <!-- Título -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-medium font-bold text-center mb-8 text-gradient animate-pulse">
            Solicitar Doação
        </h1>

        <!-- Mensagem de sucesso -->
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulário -->
        <form action="{{ route('doacao.store') }}" method="POST" id="formDoacao" class="space-y-6">
            @csrf

            <!-- Dados pessoais -->
            <h2 class="text-xl font-semibold text-gray-700">Dados Pessoais:</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nome_solicitante" placeholder="Nome*" 
                    class="w-full p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                <input type="email" name="email_solicitante" placeholder="E-mail*" 
                    class="w-full p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="CPF* (000.000.000-00)" 
                    oninput="mascararCPF(this)"
                    class="w-full p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                <input type="tel" name="telefone_solicitante" placeholder="Telefone*" 
                    class="w-full p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
            </div>

            <hr class="my-6">

            <!-- Itens solicitados -->
            <h2 class="text-xl font-semibold text-gray-700">Itens Solicitados:</h2>
            <div id="itens-container" class="space-y-4 mt-4">
                <!-- Item inicial -->
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl item-pedidodoacao">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="itens[0][descricao]" placeholder="Descrição*" 
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                        <input type="number" name="itens[0][quantidade]" placeholder="Quantidade*" min="1"
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                        <select name="itens[0][unidade]" 
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
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
                        <button type="button" class="remove-item text-red-500 text-lg">×</button>
                    </div>
                </div>
            </div>

            <!-- Botão adicionar -->
            <button type="button" id="adicionar-item" 
                class="w-full py-2 rounded-xl border-2 border-pink-400 text-pink-600 font-semibold hover:scale-105 hover:bg-pink-50 transition">
                + Adicionar outro item
            </button>

            <!-- Botões -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6">
                <button type="submit" 
                    class="bg-gradient-to-r from-red-500 to-pink-500 text-white py-2 rounded-xl font-semibold shadow-md hover:opacity-90 hover:scale-105 transition">
                    Confirmar Solicitação
                </button>
                    <a href="{{ url('/') }}" class="w-full py-2 rounded-xl border-gray-200 text-black font-medium text-center transition bg-gray-200 hover:bg-red-500 hover:scale-105 hover:text-white shadow-lg hover:shadow-xl">
                        Cancelar
                    </a>
            </div>
        </form>
    </div>

    <script>
        function mascararCPF(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            input.value = value;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('itens-container');
            const addButton = document.getElementById('adicionar-item');
            let itemCount = 1;

            addButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'p-4 bg-gray-50 border border-gray-200 rounded-xl item-pedidodoacao';
                newItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="itens[${itemCount}][descricao]" placeholder="Descrição*" 
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                        <input type="number" name="itens[${itemCount}][quantidade]" placeholder="Quantidade*" min="1"
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
                        <select name="itens[${itemCount}][unidade]" 
                            class="p-3 rounded-xl border border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 outline-none" required>
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
                        <button type="button" class="remove-item text-red-500 text-lg">×</button>
                    </div>
                `;
                container.appendChild(newItem);
                itemCount++;
            });

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
