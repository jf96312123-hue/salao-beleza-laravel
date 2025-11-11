@extends('layouts.app')

@section('title', 'Gestão de Clientes')

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
    </style>
@endpush

@section('content')

    <h1 class="h1-custom">Gestão de Clientes</h1>

    <div class="list-container">
        @if (session('sucesso'))
            <div class="alert-success">
                {{ session('sucesso') }}
            </div>
        @endif

        <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            
            @php
                $filtroAtivo = $filtroAtivo ?? null; 
                $isAniversariantesActive = ($filtroAtivo == 'aniversariantes');
                // LINHA CORRIGIDA (com 'color: white;' completo)
                $buttonStyle = $isAniversariantesActive ? 'background-color: #ffc107; color: #343a40; font-weight: bold;' : 'background-color: #007bff; color: white;';
            @endphp
            <a href="{{ route('clientes.create') }}" class="btn-list">Adicionar Novo Cliente</a>
            
            <a href="{{ route('clientes.index', ['filtro' => 'aniversariantes']) }}" 
               class="btn-list" 
               style="{{ $buttonStyle }} width: 250px; text-align: center;"
            >
                🎉 Ver Aniversariantes do Mês
            </a>
        </div>
        <div class="search-input-container">
            <label for="searchInput" style="font-weight: bold; margin-bottom: 5px;">🔍 Pesquisar Cliente:</label>
            <input type="text" id="searchInput" onkeyup="filterClientes()" placeholder="Digite o nome, telefone ou e-mail..." 
                   style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <table class="list-table" id="clientesTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Aniversário</th> 
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nome }}</td>
                        <td>
                            @if ($cliente->data_nascimento)
                                {{ \Carbon\Carbon::parse($cliente->data_nascimento)->format('d/m') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $cliente->telefone }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>
                            <a href="{{ route('clientes.edit', $cliente->id) }}" style="color: #007bff; text-decoration: none; margin-right: 10px;">Editar</a>
                            
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline;">
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
                        <td colspan="5" class="empty">Nenhum cliente cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>
        function filterClientes() {
            // 1. Declarar variáveis e preparar os termos de pesquisa
            const input = document.getElementById("searchInput");
            const searchTerms = input.value.trim().toUpperCase().split(/\s+/).filter(term => term.length > 0);
            
            const table = document.getElementById("clientesTable");
            const tr = table.getElementsByTagName("tr");
            
            let i, allTermsMatch;

            // 2. Loop por todas as linhas da tabela (começa em 1 para ignorar o cabeçalho)
            for (i = 1; i < tr.length; i++) {
                
                // CRÍTICO: Pega os textos de todas as colunas relevantes
                const nome = (tr[i].getElementsByTagName("td")[0].textContent || "").toUpperCase();
                const aniversario = (tr[i].getElementsByTagName("td")[1].textContent || "").toUpperCase(); 
                const telefone = (tr[i].getElementsByTagName("td")[2].textContent || "").toUpperCase(); 
                const email = (tr[i].getElementsByTagName("td")[3].textContent || "").toUpperCase(); 
                
                // Concatena todos os campos da linha para pesquisa
                const rowText = nome + " " + aniversario + " " + telefone + " " + email;

                allTermsMatch = true;
                
                if (searchTerms.length > 0) {
                    // 3. Verifica se TODOS os termos de pesquisa estão presentes na linha
                    allTermsMatch = searchTerms.every(term => {
                        return rowText.indexOf(term) > -1;
                    });
                } 
                
                // 4. Aplica o filtro
                if (searchTerms.length === 0 || allTermsMatch) {
                    tr[i].style.display = ""; // Mostra a linha
                } else {
                    tr[i].style.display = "none"; // Esconde a linha
                }
            }
        }
    </script>
@endpush