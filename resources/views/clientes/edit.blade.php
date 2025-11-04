<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        h1 { color: #333; }
        form { max-width: 600px; margin: 20px 0; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        .btn-container { text-align: right; }
        .btn { padding: 10px 15px; border: none; border-radius: 5px; text-decoration: none; color: white; cursor: pointer; }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; margin-right: 10px; }
    </style>
</head>
<body>

    <h1>Editar Cliente: {{ $cliente->nome }}</h1>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf

        @method('PUT')

        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="{{ $cliente->nome }}" required>
        </div>

        <div>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="{{ $cliente->telefone }}">
        </div>
        
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="{{ $cliente->email }}">
        </div>

        <div>
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="{{ $cliente->data_nascimento }}">
        </div>

        <div>
            <label for="observacoes">Observações:</label>
            <textarea id="observacoes" name="observacoes" rows="3">{{ $cliente->observacoes }}</textarea>
        </div>

        <div class="btn-container">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>

    </form>

</body>
</html>