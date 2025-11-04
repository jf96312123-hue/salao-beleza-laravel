@extends('layouts.app')

@section('title', 'Gestão de Clientes')

{{-- Injeta CSS extra para o layout (Opcional) --}}
@push('styles')
<style>
    /* CSS para estilizar elementos específicos desta view dentro do layout */
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

<h1 class="h1-custom">Gestão de Clientes</h1>

@if (session('sucesso'))
<div class="alert-success">
    {{ session('sucesso') }}
</div>
@endif

<a href="{{ route('clientes.create') }}" class="btn-list">Adicionar Novo Cliente</a>

<table class="list-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clientes as $cliente)
        <tr>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->telefone }}</td>
            <td>{{ $cliente->email }}</td>
            <td>
                <a href="{{ route('clientes.edit', $cliente->id) }}" style="color: #007bff; text-decoration: none;">Editar</a>

                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="color: #dc3545; text-decoration: none; border: none; background: none; cursor: pointer; padding: 0;"
                        onclick="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.')">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="empty">Nenhum cliente cadastrado.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection