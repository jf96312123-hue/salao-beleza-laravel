@extends('layouts.app')

@section('title', 'Editar Cliente')

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
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container textarea {
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
        .alert-error {
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')

    <h1 class="h1-custom">Editar Cliente: {{ $cliente->nome }}</h1>

    <div class="form-container">
        @if ($errors->any())
            <div class="alert-error">
                <p style="font-weight: bold;">Por favor, corrija os seguintes erros:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            
            @csrf
            @method('PUT') <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required>
            </div>

            <div>
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $cliente->telefone) }}">
            </div>
            
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $cliente->email) }}">
            </div>

            <div>
                <label for="data_nascimento">Data de Nascimento (Aniversário):</label>
                <input type="date" id="data_nascimento" name="data_nascimento" 
                       value="{{ old('data_nascimento', \Carbon\Carbon::parse($cliente->data_nascimento)->format('Y-m-d')) }}" 
                       required>
            </div>

            <div>
                <label for="observacoes">Observações:</label>
                <textarea id="observacoes" name="observacoes" rows="3">{{ old('observacoes', $cliente->observacoes) }}</textarea>
            </div>

            <div class="btn-container">
                <a href="{{ route('clientes.index') }}" class="btn-form btn-secondary">Cancelar</a>
                <button type="submit" class="btn-form btn-primary">Salvar Alterações</button>
            </div>

        </form>
    </div>

@endsection