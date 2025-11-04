@extends('layouts.app')

@section('title', 'Adicionar Item ao Estoque')

@push('styles')
    <style>
        /* Estilos do formulário */
        .form-container {
            max-width: 600px; 
            margin-top: 20px; 
            padding: 20px; 
            background-color: #fff; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        .form-container div { 
            margin-bottom: 15px; 
        }
        .form-container label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="date"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box; 
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-container { text-align: right; }
        .btn-form { 
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px; 
            text-decoration: none; 
            color: white; 
            cursor: pointer; 
        }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; margin-right: 10px; }
        .h1-custom { 
            font-size: 32px; 
            font-weight: bold;
            margin-top: 20px; 
            margin-bottom: 25px; 
            text-align: center; 
            width: 100%;
        }
    </style>
@endpush

@section('content')

    <h1 class="h1-custom">Adicionar Item ao Estoque</h1>

    <div class="form-container">
        <form action="{{ route('estoques.store') }}" method="POST">
            
            @csrf

            <div>
                <label for="nome_produto">Nome do Produto:</label>
                <input type="text" id="nome_produto" name="nome_produto" required>
            </div>

            <div>
                <label for="quantidade">Quantidade de Itens:</label>
                <input type="number" id="quantidade" name="quantidade" step="1" min="1" required>
            </div>
            
            <div>
                <label for="preco_unitario">Preço Unitário de Compra (R$):</label>
                <input type="number" id="preco_unitario" name="preco_unitario" step="0.01" min="0" required>
            </div>

            <div>
                <label for="data_compra">Data da Compra:</label>
                <input type="date" id="data_compra" name="data_compra" required>
            </div>


            <div class="btn-container">
                <a href="{{ route('estoques.index') }}" class="btn-form btn-secondary">Cancelar</a>
                <button type="submit" class="btn-form btn-primary">Salvar Item</button>
            </div>

        </form>
    </div>

@endsection