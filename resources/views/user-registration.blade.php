<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub - Cadastro ONG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen relative bg-gray-50">

    
    <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/donation-pattern.png') }}');">

        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>

    <!-- Conteúdo principal -->
    <div class="relative z-20 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="bg-white/95 backdrop-blur-md p-6 sm:p-8 md:p-10 rounded-3xl shadow-2xl max-w-md w-full text-center border border-white/30 mx-auto">

                <!-- Logo/ícone -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-800 to-green-500 rounded-2xl flex items-center justify-center shadow-lg text-white">
                    <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg><!-- <img src="/images/food-delivery.png" alt="Ícone ONGHub" class="w-10 h-10 object-contain"> -->
                </div>
            </div>

            <!-- Título -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-medium font-bold mb-6  bg-gradient-to-r from-emerald-800 to-green-500 bg-clip-text text-transparent animate-pulse">
                Cadastro ONG
            </h1>

            <!-- Mensagem de erro -->
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm text-left">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Formulário -->
            <form method="POST" action="{{ route('ong.register') }}" class="space-y-4">
                @csrf

                <input type="text" name="nome" placeholder="Nome da ONG*" value="{{ old('nome') }}" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                <input type="text" name="cnpj" placeholder="CNPJ*" value="{{ old('cnpj') }}" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                <input type="email" name="email" placeholder="E-mail*" value="{{ old('email') }}" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                <input type="text" name="telefone" placeholder="Telefone*" value="{{ old('telefone') }}" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                <textarea name="endereco" placeholder="Endereço Completo*" rows="2" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">{{ old('endereco') }}</textarea>
                <div class="text-left">
                    <input type="password" id="password" name="password" placeholder="Senha*" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">

                    <!-- Caixa de requisitos inicialmente oculta -->
                    <div id="password-rules" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700">
                        <p class="font-medium text-gray-800 mb-1">A senha deve conter:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li id="req-min" class="text-red-500">❌ Mínimo 8 caracteres</li>
                            <li id="req-letter" class="text-red-500">❌ Pelo menos 1 letra</li>
                            <li id="req-mixed" class="text-red-500">❌ Letras maiúsculas e minúsculas</li>
                            <li id="req-number" class="text-red-500">❌ Pelo menos 1 número</li>
                            <li id="req-symbol" class="text-red-500">❌ Pelo menos 1 símbolo especial</li>
                        </ul>
                    </div>
                </div>

                <script>
                    const passwordInput = document.getElementById("password");
                    const passwordRules = document.getElementById("password-rules");

                    const requirements = {
                        min: document.getElementById("req-min"),
                        letter: document.getElementById("req-letter"),
                        mixed: document.getElementById("req-mixed"),
                        number: document.getElementById("req-number"),
                        symbol: document.getElementById("req-symbol"),
                    };

                    // Mostra quando foca no input
                    passwordInput.addEventListener("focus", () => {
                        passwordRules.classList.remove("hidden");
                    });

                    // Esconde quando perde o foco (opcional, pode comentar se quiser sempre visível depois de abrir)
                    passwordInput.addEventListener("blur", () => {
                        if (passwordInput.value === "") {
                            passwordRules.classList.add("hidden");
                        }
                    });

                    // Validação em tempo real
                    passwordInput.addEventListener("input", function () {
                        const value = passwordInput.value;

                        toggleRequirement(requirements.min, value.length >= 8);
                        toggleRequirement(requirements.letter, /[a-zA-Z]/.test(value));
                        toggleRequirement(requirements.mixed, /[a-z]/.test(value) && /[A-Z]/.test(value));
                        toggleRequirement(requirements.number, /\d/.test(value));
                        toggleRequirement(requirements.symbol, /[^A-Za-z0-9]/.test(value));
                    });

                    function toggleRequirement(element, condition) {
                        if (condition) {
                            element.classList.remove("text-red-500");
                            element.classList.add("text-green-600");
                            element.textContent = element.textContent.replace("❌", "✅");
                        } else {
                            element.classList.remove("text-green-600");
                            element.classList.add("text-red-500");
                            element.textContent = element.textContent.replace("✅", "❌");
                        }
                    }
                </script>
                <input type="password" name="password_confirmation" placeholder="Confirme a Senha*" class="w-full p-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">

                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-2 rounded-xl font-medium hover:scale-105 transition transform shadow-lg hover:shadow-xl">
                    Cadastrar ONG
                </button>
                
                <button type="button"
                        onclick="window.location.href='{{ url('/') }}'" 
                        class="w-full text-black py-2 rounded-xl font-medium bg-gray-200 hover:bg-red-500 hover:scale-105 hover:text-white transition shadow-lg hover:shadow-xl"> 
                        Cancelar
                </button>
            </form>
        </div>
    </div>

    <style>
        /* Gradiente do texto */
        .text-gradient {
            background: linear-gradient(to right, #059669, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        /* Animação leve */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

</body>
</html>
