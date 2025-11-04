<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Funcionário</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        h1 { color: #333; }
        form { max-width: 600px; margin: 20px 0; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box; 
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-container { text-align: right; }
        .btn { padding: 10px 15px; border: none; border-radius: 5px; text-decoration: none; color: white; cursor: pointer; }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; margin-right: 10px; }
    </style>
</head>
<body>

    <h1>Adicionar Novo Funcionário</h1>

    <form action="{{ route('funcionarios.store') }}" method="POST">

        @csrf

        <div>
            <label for="name">Nome do Funcionário:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmar Senha:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>


        <div class="btn-container">
            <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Funcionário</button>
        </div>

    </form>

</body>
</html>