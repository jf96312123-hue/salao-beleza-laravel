@extends('layouts.app')

@section('title', 'Gestão de Estoque')

@push('styles')
    <style>
        /* Reusando e ajustando os estilos de tabela e botão para consistência */
        .list-container {
             display: flex;
             flex-direction: column;
             align-items: center;
             width: 100%;
             max-width: 1000px; /* Limita a largura da tabela */
        }
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
            align-self: flex-start; /* Alinha o botão à esquerda do container */
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
            width: 100%;
        }
        /* Estilos do Título (para ficar maior e em destaque) */
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

    <h1 class="h1-custom">Gestão de Estoque</h1>

    <div class="list-container">
        <a href="{{ route('estoques.create') }}" class="btn-list">Adicionar Novo Item</a>

        <table class="list-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd.</th>
                    <th>Preço Unitário (R$)</th>
                    <th>Data de Compra</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($itens as $item)
                    <tr>
                        <td>{{ $item->nome_produto }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->data_compra)->format('d/m/Y') }}</td>
                        <td>
                            <a href="#">Editar</a>
                            <a href="#">Excluir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">Nenhum item em estoque cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection