<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/food-delivery.png') }}">
    <title>ONGHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animações customizadas */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-float-delay { animation: float 3s ease-in-out infinite 1.5s; }

        /* Gradiente para texto */
        .text-gradient {
            background: linear-gradient(to right, #059669, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="min-h-screen relative">

    <!-- Background com imagem de doação -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/donation-pattern.png') }}');">

        <!-- Overlay para melhorar legibilidade -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Overlay escuro -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>

    <!-- Elementos flutuantes animados -->
    <div class="absolute top-1/4 left-1/4 animate-bounce delay-1000 z-10">
        <svg class="w-6 h-6 text-red-400/70" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
    </div>
    <div class="absolute top-1/3 right-1/4 animate-pulse delay-2000 z-10">
        <svg class="w-8 h-8 text-emerald-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
        </svg>
    </div>
    <div class="absolute bottom-1/3 left-1/3 animate-bounce delay-500 z-10">
        <svg class="w-7 h-7 text-blue-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </div>

    <!-- Conteúdo principal -->
    <div class="relative z-20 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="bg-white/95 backdrop-blur-md p-6 sm:p-8 md:p-10 rounded-3xl shadow-2xl max-w-md sm:max-w-lg w-full text-center border border-white/30 mx-auto">

            <!-- Logo/Ícone principal -->
             <div class="mb-8 flex justify-center animate-pulse">
                 <div class="relative flex gap-x-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <img src="/images/food-delivery.png" alt="Ícone" class="w-8 h-8 sm:w-10 sm:h-10 object-contain"/>
                    </div>

                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                            <path d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Título e descrição -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-gradient animate-pulse">
                ONGHub
            </h1>
            <!-- <p class="text-base sm:text-lg text-gray-600 mb-2 font-medium animate-pulse">
                Conectando Corações Solidários
            </p> -->
            <p class="text-sm sm:text-base text-gray-500 mb-6 sm:mb-8 leading-relaxed px-2 overflow-hidden whitespace-nowrap animate-typing">
                Unindo ONGs e doadores para o combate à fome!
            </p>

            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-emerald-500 text-white text-sm sm:text-base font-semibold px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
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

            <!-- Botões de ação -->
            <div class="space-y-4">
                <!-- Botões principais -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 sm:mb-6">
                    <a href="{{ route('login') }}" class="group bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 font-medium text-sm sm:text-base">
                        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('user.registration') }}" class="group bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 font-medium text-sm sm:text-base">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Cadastrar ONG
                    </a>
                </div>

                <!-- Botões secundários -->
                <div class="space-y-3">
                    <a href="{{ route('intencaodoacao.create') }}" class="w-full group bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 font-medium text-sm sm:text-base">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        Quero Doar Alimentos
                    </a>

                    <a href="{{ route('pedidodoacao.create') }}" class="w-full group bg-gradient-to-r from-red-500 to-pink-500 text-white py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 font-medium text-sm sm:text-base">

                        <svg class="w-5 h-5 text-white group-hover:-rotate-45 transition-transform" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                            <g stroke-linecap="round" stroke-linejoin="round" stroke= "currentColor" stroke-width="2" transform="translate(1.4066 1.4066) scale(2.81 2.81)">
                                <path d="M51.456 47.291c-0.256 0-0.512-0.098-0.707-0.293L29.812 26.061c-5.952-5.952-5.952-15.637 0-21.589C32.694 1.588 36.528 0 40.605 0S48.517 1.588 51.4 4.472l0.056 0.056 0.056-0.056C54.395 1.588 58.229 0 62.307 0c4.077 0 7.91 1.588 10.794 4.472l0 0 0 0c5.952 5.952 5.952 15.637 0 21.589L52.163 46.998C51.968 47.193 51.712 47.291 51.456 47.291zM40.605 2c-3.543 0-6.875 1.38-9.38 3.886-5.172 5.172-5.172 13.588 0 18.761l20.23 20.23 20.23-20.23c5.172-5.173 5.172-13.589 0-18.761l0 0C69.181 3.38 65.85 2 62.307 2c-3.544 0-6.875 1.38-9.381 3.886l-0.763 0.763c-0.391 0.391-1.023 0.391-1.414 0l-0.763-0.763C47.48 3.38 44.149 2 40.605 2z"/>
                                <path d="M43.036 90c-2.937 0-5.844-1.081-8.666-2.129-3.111-1.156-6.323-2.35-9.521-2.068l-7.79 0.691V56.157l4.222-0.375c2.65-0.231 4.867 0.798 7.011 1.797 2.025 0.943 3.941 1.844 6.142 1.654l14.064-2.552c5.025-0.854 7.791 2.064 9.468 4.721l15.39-7.154c5.769-2.59 12.243 0.01 16.131 6.464 1.011 1.678 0.448 3.906-1.253 4.968-1.993 1.243-3.979 2.487-5.943 3.719C65.158 80.133 50.363 89.403 44.041 89.956 43.706 89.986 43.371 90 43.036 90zM25.834 83.76c3.214 0 6.268 1.135 9.232 2.236 3.07 1.142 5.97 2.218 8.799 1.968 5.843-0.511 21.154-10.104 37.363-20.261 1.966-1.231 3.952-2.477 5.946-3.721 0.78-0.486 1.049-1.491 0.599-2.239-3.341-5.544-8.803-7.828-13.586-5.676L57.16 63.982l-0.456-0.796c-1.52-2.648-3.639-5.256-7.859-4.535l-14.151 2.563c-2.825 0.233-5.074-0.812-7.246-1.823-1.992-0.929-3.879-1.808-5.989-1.617l-2.399 0.213v26.321l5.613-0.498C25.062 83.776 25.449 83.76 25.834 83.76z"/>
                                <path d="M39.091 75.237c-0.467 0-0.885-0.328-0.979-0.804-0.108-0.542 0.243-1.068 0.785-1.177 5.57-1.113 11.833-3.661 19.122-7.779l-1.314-2.291c-0.275-0.479-0.109-1.091 0.369-1.365 0.479-0.273 1.091-0.108 1.365 0.369l1.813 3.161c0.274 0.479 0.109 1.09-0.368 1.364-7.853 4.521-14.589 7.302-20.596 8.502C39.222 75.231 39.156 75.237 39.091 75.237z"/>
                                <path d="M15.54 90H3.528c-1.941 0-3.52-1.579-3.52-3.52V54.192c0-1.94 1.579-3.52 3.52-3.52H15.54c1.941 0 3.52 1.579 3.52 3.52V86.48C19.059 88.421 17.48 90 15.54 90zM3.528 52.673c-0.838 0-1.52 0.682-1.52 1.52V86.48c0 0.838 0.682 1.52 1.52 1.52H15.54c0.838 0 1.52-0.682 1.52-1.52V54.192c0-0.838-0.682-1.52-1.52-1.52H3.528z"/>
                            </g>
                        </svg>
                        Solicitar Doação
                    </a>

                    <a href="{{ route('reativar.conta') }}" class="w-full group bg-gradient-to-r from-gray-500 to-gray-600 text-white py-2 sm:py-2.5 px-4 sm:px-6 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-xs sm:text-sm font-medium">
                        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reativar Conta
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <!-- <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="group cursor-pointer">
                        <div class="text-xl sm:text-2xl font-bold text-emerald-600 group-hover:scale-110 transition-transform">50+</div>
                        <div class="text-xs sm:text-sm text-gray-500">ONGs Ativas</div>
                    </div>
                    <div class="group cursor-pointer">
                        <div class="text-xl sm:text-2xl font-bold text-blue-600 group-hover:scale-110 transition-transform">1.2k</div>
                        <div class="text-xs sm:text-sm text-gray-500">Doações</div>
                    </div>
                    <div class="group cursor-pointer">
                        <div class="text-xl sm:text-2xl font-bold text-red-600 group-hover:scale-110 transition-transform">800+</div>
                        <div class="text-xs sm:text-sm text-gray-500">Famílias Ajudadas</div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Rodapé sutil -->
    <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 text-center z-20">
        <p class="text-xs sm:text-sm text-white">
            Juntos contra a fome • ONGHub 2025
        </p>
    </div>

</body>
</html>
