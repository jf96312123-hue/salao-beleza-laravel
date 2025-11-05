<?php

namespace App\Http\Controllers;

use App\Models\Estoque; 
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostrar a lista de itens em estoque e calcular o valor total)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. Busca todos os itens de estoque
        $itens = Estoque::orderBy('nome_produto')->get();

        // 2. CALCULAR O VALOR TOTAL DO ESTOQUE
        $valorTotalEstoque = $itens->reduce(function ($carry, $item) {
            // Multiplica Quantidade * Preço Unitário e soma
            return $carry + ($item->quantidade * $item->preco_unitario);
        }, 0); 

        // 3. Retorna a view 'estoques.index' e passa as variáveis
        return view('estoques.index', [
            'itens' => $itens,
            'valorTotalEstoque' => $valorTotalEstoque,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
            'data_compra' => 'required|date_format:Y-m-d',
        ]);

        // 2. Criação do Item de Estoque
        Estoque::create($request->all());

        // 3. Redirecionamento
        return redirect()->route('estoques.index')
                         ->with('sucesso', 'Item de estoque cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Não implementado
    }

    /**
     * Show the form for editing the specified resource.
     * (Mostra o formulário de edição)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 1. Encontra o item de estoque
        $item = Estoque::findOrFail($id);
        
        // 2. Retorna a view de edição, passando o item
        return view('estoques.edit', ['item' => $item]);
    }

    /**
     * Update the specified resource in storage.
     * (Atualiza o item de estoque)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. Encontra o item
        $item = Estoque::findOrFail($id);

        // 2. Validação dos dados
        $request->validate([
            'nome_produto' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'preco_unitario' => 'required|numeric|min:0',
            'data_compra' => 'required|date_format:Y-m-d',
        ]);

        // 3. Atualiza os dados
        $item->update($request->all());

        // 4. Redireciona
        return redirect()->route('estoques.index')
                         ->with('sucesso', 'Item atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     * (Exclui o item de estoque)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1. Encontra o item
        $item = Estoque::findOrFail($id);

        // 2. Exclui o item
        $item->delete();

        // 3. Redireciona
        return redirect()->route('estoques.index')
                         ->with('sucesso', 'Item excluído com sucesso!');
    }
}