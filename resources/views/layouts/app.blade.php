<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Salão')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding-top: 60px;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 25px;
            font-size: 16px;
            padding: 5px 0;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #ffc107;
        }

        .content {
            padding: 20px;
            /* Mudamos para coluna e centralizamos o conteúdo no meio */
            display: flex;
            flex-direction: column;
            /* Organiza os itens verticalmente */
            align-items: center;
            /* Centraliza horizontalmente o conteúdo (título, botão, tabela) */
            width: 100%;
            /* Garante que o container ocupe toda a largura */
        }

        .h1-custom {
            margin-top: 0;
            margin-bottom: 20px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar">
        <a href="{{ url('/agendamentos') }}">📅 Agenda</a>
        <a href="{{ url('/clientes') }}">👤 Clientes</a>
        <a href="{{ url('/servicos') }}">💅 Serviços</a>
        <a href="{{ url('/funcionarios') }}">✂️ Funcionários</a>
        <a href="{{ url('/estoques') }}">📦 Estoque</a> </nav>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>