<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Serviço</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        h1 { color: #333; }
        form { max-width: 600px; margin: 20px 0; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"],
        input[type="number"],
        textarea {
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

    <h1>Adicionar Novo Serviço</h1>

    <form action="{{ route('servicos.store') }}" method="POST">

        @csrf

        <div>
            <label for="nome">Nome do Serviço:</label>
            <input type="text" id="nome" name="nome" required>
        </div>

        <div>
            <label for="descricao">Descrição (opcional):</label>
            <textarea id="descricao" name="descricao" rows="3"></textarea>
        </div>

        <div>
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required>
        </div>

        <div>
            <label for="duracao_minutos">Duração (em minutos):</label>
            <input type="number" id="duracao_minutos" name="duracao_minutos" step="1" min="0" required>
        </div>

        <div class="btn-container">
            <a href="{{ route('servicos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Serviço</button>
        </div>

    </form>

</body>
</html>