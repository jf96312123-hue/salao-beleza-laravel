<?php

namespace App\Http\Controllers;

use App\Models\Servico; // 1. IMPORTAMOS O MODEL DE SERVIÇO
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostrar a lista de serviços)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 2. Busca todos os serviços no banco, ordenados por nome
        $servicos = Servico::orderBy('nome')->get();

        // 3. Retorna a view 'servicos.index' e passa a variável $servicos para ela
        return view('servicos.index', ['servicos' => $servicos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Apenas retorna a view que contém o formulário de criação
        return view('servicos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0', // Obrigatório, numérico, não-negativo
            'duracao_minutos' => 'required|integer|min:0', // Obrigatório, inteiro, não-negativo
        ]);

        // 2. Criação do Serviço
        // Usa o $fillable que definimos no Model 'Servico.php'
        Servico::create($request->all());

        // 3. Redirecionamento com mensagem de sucesso
        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // (Vazio por enquanto)
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 1. Encontra o serviço no banco de dados pelo ID
        $servico = Servico::findOrFail($id);

        // 2. Retorna a view de edição e passa o serviço encontrado para ela
        return view('servicos.edit', ['servico' => $servico]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. Encontra o serviço que queremos editar
        $servico = Servico::findOrFail($id);

        // 2. Validação (mesmas regras do 'store')
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'duracao_minutos' => 'required|integer|min:0',
        ]);

        // 3. Atualiza os dados do serviço no banco
        $servico->update($request->all());

        // 4. Redireciona de volta para a lista (index) com uma mensagem
        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1. Encontra o serviço
        $servico = Servico::findOrFail($id);

        // 2. Exclui o serviço do banco de dados
        $servico->delete();

        // 3. Redireciona de volta para a lista com uma mensagem
        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço excluído com sucesso!');
    }
}
