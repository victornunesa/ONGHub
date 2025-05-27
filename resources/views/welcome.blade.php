<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-10 rounded shadow max-w-md w-full text-center">
            <h1 class="text-3xl font-bold mb-4">Bem-vindo ao ONGHub</h1>
            <p class="text-lg text-gray-600 mb-6">Acesse ou cadastre sua ONG para começar.</p>

            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Login</a>
                <a href="{{ route('user.registration') }}" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Cadastrar ONG</a>
                <a href="{{ route('reativar.conta') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition">Reativar Conta</a>
                <a href="{{ route('intencaodoacao.create') }}" class="bg-yellow-400 text-white py-2 px-4 rounded hover:bg-yellow-500 transition">Quero doar</a>
                <a href="{{ route('pedidodoacao.create') }}" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">Solicitar doação</a>
            </div>
        </div>
    </div>

</body>
</html>