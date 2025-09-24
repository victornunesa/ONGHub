<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub - Reativar Conta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-gradient {
            background: linear-gradient(to right, #9ca3af, #6b7280); /* Cinza suave */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-gray-700 to-gray-600 min-h-screen flex items-center justify-center p-4 relative">

    <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat animate-pulse" 
         style="background-image: url('{{ asset('images/donation-pattern.png') }}');">
         
        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>
    

    <!-- Container do formulário -->
    <div class="relative w-full max-w-md bg-gray-50/80 rounded-2xl shadow-lg p-6 md:p-10 z-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-900 to-gray-400 rounded-2xl flex items-center justify-center shadow-lg text-white">
                <svg class="w-10 h-10 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
        </div>

        <!-- Título -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-medium font-bold text-center mb-8 text-gradient bg-gradient-to-br from-gray-900 to-gray-400">
            Reativar Conta
        </h1>

        <!-- Mensagem de erros -->
        @if($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 text-center">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulário -->
        <form action="{{ route('reativar.conta.processar') }}" method="POST" class="space-y-6">
            @csrf

            <input type="email" name="email" id="email" placeholder="E-mail da Conta*" 
                class="w-full p-3 rounded-xl border border-gray-300 bg-gray-50/90 focus:border-gray-400 focus:ring focus:ring-gray-300 outline-none" required value="{{ old('email') }}">

            <input type="password" name="password" id="password" placeholder="Senha*" 
                class="w-full p-3 rounded-xl border border-gray-300 bg-gray-50/90 focus:border-gray-400 focus:ring focus:ring-gray-300 outline-none" required>

            <button type="submit" 
                class="w-full py-2 bg-gradient-to-r from-gray-900 to-gray-400 text-white rounded-xl font-semibold shadow-md hover:opacity-90 hover:scale-105 transition">
                Reativar Conta
            </button>

            <button type="button"
                        onclick="window.location.href='{{ url('/') }}'" 
                        class="w-full text-black py-2 rounded-xl font-medium bg-gray-200 hover:bg-red-500 hover:scale-105 hover:text-white transition shadow-lg hover:shadow-xl"> 
                        Cancelar
                </button>
        </form>
    </div>

</body>
</html>
