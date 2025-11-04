@extends('layouts.app')

@section('title', 'Gestão de Serviços')

{{-- Injeta o CSS específico para a tabela e botões, usando os estilos definidos no layout --}}
@push('styles')
<style>
    /* Reusando os estilos de tabela e botão definidos na view de Clientes/layout para consistência */
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

    .btn-list {
        display: inline-block;
        padding: 10px 15px;
        font-size: 16px;
        color: #fff;
        background-color: #28a745;
        border-radius: 5px;
        text-decoration: none;
        margin-bottom: 20px;
    }

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
    }
</style>
@endpush

@section('content')

<h1 class="h1-custom">Gestão de Serviços</h1>

@if (session('sucesso'))
<div class="alert-success">
    {{ session('sucesso') }}
</div>
@endif

<a href="{{ route('servicos.create') }}" class="btn-list">Adicionar Novo Serviço</a>

<table class="list-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço (R$)</th>
            <th>Duração (min)</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($servicos as $servico)
        <tr>
            <td>{{ $servico->nome }}</td>
            <td>{{ $servico->descricao }}</td>
            <td>R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
            <td>{{ $servico->duracao_minutos }} min</td>
            <td>
                <a href="{{ route('servicos.edit', $servico->id) }}" style="color: #007bff; text-decoration: none;">Editar</a>

                <form action="{{ route('servicos.destroy', $servico->id) }}" method="POST" style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="color: #dc3545; text-decoration: none; border: none; background: none; cursor: pointer; padding: 0;"
                        onclick="return confirm('Tem certeza que deseja excluir este serviço?')">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="empty">Nenhum serviço cadastrado.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection