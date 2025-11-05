@extends('layouts.app')

@section('title', 'Gestão de Estoque')

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
        /* Estilo do Input de Pesquisa */
        .search-input-container {
            width: 100%; 
            margin-bottom: 20px;
        }
        .search-input-container input {
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box; 
            border: 1px solid #ddd; 
            border-radius: 4px;
        }
        /* Valor Total */
        .total-value-container {
             width: 100%; 
             max-width: 1000px; 
             text-align: right; 
             margin-bottom: 20px; 
             font-size: 1.2em; 
             font-weight: bold;
        }
    </style>
@endpush

@section('content')

    <h1 class="h1-custom">Gestão de Estoque</h1>

    <div class="total-value-container">
        Valor Total do Estoque: 
        <span style="color: #28a745;">
            R$ {{ number_format($valorTotalEstoque, 2, ',', '.') }}
        </span>
    </div>
    <div class="list-container">
        
        @if (session('sucesso'))
        <div class="alert-success">
            {{ session('sucesso') }}
        </div>
        @endif

        <a href="{{ route('estoques.create') }}" class="btn-list">Adicionar Novo Item</a>

        <div class="search-input-container">
            <label for="searchInput" style="font-weight: bold; margin-bottom: 5px;">Pesquisar Produto:</label>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Digite o nome do produto...">
        </div>
        <table class="list-table" id="estoqueTable">
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
                            <a href="{{ route('estoques.edit', $item->id) }}" style="color: #007bff; text-decoration: none; margin-right: 10px;">Editar</a>
                            
                            <form action="{{ route('estoques.destroy', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="color: #dc3545; text-decoration: none; border: none; background: none; cursor: pointer; padding: 0;"
                                        onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                    Excluir
                                </button>
                            </form>
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

@push('scripts')
    <script>
        function filterTable() {
            const input = document.getElementById("searchInput");
            
            // Divide o texto em palavras (termos), remove espaços em branco, e converte para maiúsculas
            const searchTerms = input.value.trim().toUpperCase().split(/\s+/).filter(term => term.length > 0);
            
            const table = document.getElementById("estoqueTable");
            const tr = table.getElementsByTagName("tr");
            
            let td, txtValue;

            for (let i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; 
                
                if (td) {
                    txtValue = (td.textContent || td.innerText).toUpperCase(); 
                    
                    // Verifica se TODOS os termos de pesquisa estão contidos no texto do produto.
                    const allTermsMatch = searchTerms.every(term => {
                        return txtValue.indexOf(term) > -1;
                    });
                    
                    // Aplica o filtro
                    if (searchTerms.length === 0 || allTermsMatch) {
                        tr[i].style.display = ""; 
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
    </script>
@endpush