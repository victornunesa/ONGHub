<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animação flutuante */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }

        /* Gradiente para textos */
        .text-gradient {
            background: linear-gradient(to right, #059669, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center relative">

    <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/donation-pattern.png') }}');">
         
        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>


    <!-- Card de login -->
    <div class="relative z-10 bg-white/95 backdrop-blur-md p-8 rounded-3xl shadow-2xl max-w-md w-full">
        
        <!-- Logo/ícone -->
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg text-white rotate-180">
                <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg><!-- <img src="/images/food-delivery.png" alt="Ícone ONGHub" class="w-10 h-10 object-contain"> -->
            </div>
        </div>

        <!-- Título -->
        <h1 class="text-3xl font-bold mb-4 text-center text-gradient leading-relaxed animate-pulse">Login</h1>
        <!-- <p class="text-center text-gray-500 mb-6">Conectando ONGs e doadores para o combate à fome</p> -->

        <!-- Formulário -->
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-4 py-2">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-4 py-2">
            </div>

            <button type="submit" 
                    class="w-full py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-medium rounded-xl shadow-lg hover:from-blue-500 hover:to-blue-800 transition-all duration-300 transform hover:scale-105">
                Entrar
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


