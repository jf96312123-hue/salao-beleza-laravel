<?php

namespace App\Http\Controllers;

use App\Models\Estoque; 
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index()
    {
        $itens = Estoque::orderBy('nome_produto')->get();
        return view('estoques.index', ['itens' => $itens]);
    }

    public function create()
    {
        return view('estoques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'nome_produto' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'preco_unitario' => 'required|numeric|min:0',
            'data_compra' => 'required|date',
        ]);

        // 2. Criação do Item de Estoque
        Estoque::create($request->all());

        // 3. Redirecionamento com mensagem de sucesso
        return redirect()->route('estoques.index')
                         ->with('sucesso', 'Item de estoque cadastrado com sucesso!');
    }

    public function show($id) { /* Não implementado */ }
    public function edit($id) { /* Não implementado */ }
    public function update(Request $request, $id) { /* Não implementado */ }
    public function destroy($id) { /* Não implementado */ }
}