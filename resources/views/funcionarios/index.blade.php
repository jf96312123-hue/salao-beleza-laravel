@extends('layouts.app')

@section('title', 'Gestão de Funcionários')

@push('styles')
    <style>
        /* Estilos do Container Principal */
        .list-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1000px;
        }
        /* Estilos de Tabela (Reusados) */
        .list-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        .list-table th,
        .list-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .list-table th {
            background-color: #007bff;
            color: white;
        }
        .list-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        /* Estilos de Botão (Reusados) */
        .btn-list {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            color: #fff;
            background-color: #28a745;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            align-self: flex-start;
        }
        /* Alertas */
        .empty {
            padding: 20px;
            text-align: center;
            font-style: italic;
        }
        .alert-success {
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
        }
        /* Título */
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

    <h1 class="h1-custom">Gestão de Funcionários</h1>

    <div class="list-container">
        @if (session('sucesso'))
            <div class="alert-success">
                {{ session('sucesso') }}
            </div>
        @endif

        <a href="{{ route('funcionarios.create') }}" class="btn-list">Adicionar Novo Funcionário</a>

        <table class="list-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($funcionarios as $funcionario)
                    <tr>
                        <td>{{ $funcionario->name }}</td>
                        <td>{{ $funcionario->telefone }}</td>
                        
                        <td>
                            <form action="{{ route('funcionarios.destroy', $funcionario->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="color: #dc3545; text-decoration: none; border: none; background: none; cursor: pointer; padding: 0;"
                                        onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                        </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">Nenhum funcionário cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

{{-- Esta página não precisa de JavaScript customizado, então não há @push('scripts') --}}