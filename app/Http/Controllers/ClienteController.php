<?php

namespace App\Http\Controllers;

// Precisamos importar o Model de Cliente para usá-lo
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Para a regra de 'email único' na atualização

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostrar a lista de clientes)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. Busca todos os clientes no banco, ordenados por nome
        $clientes = Cliente::orderBy('nome')->get();

        // 2. Retorna a view 'clientes.index' e passa a variável $clientes para ela
        return view('clientes.index', ['clientes' => $clientes]);
    }

    /**
     * Show the form for creating a new resource.
     * (Mostrar o formulário de criação)
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Apenas retorna a view que contém o formulário
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     * (Salvar o novo cliente no banco)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        // Se a validação falhar, o Laravel automaticamente volta para o formulário
        // e exibe os erros (se a view estiver preparada para isso).
        $request->validate([
            'nome' => 'required|string|max:255', // Nome é obrigatório
            'email' => 'nullable|email|unique:clientes', // Email é opcional, mas deve ser válido e único na tabela 'clientes'
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        // 2. Criação do Cliente
        // Se a validação passar, cria o novo cliente.
        // O $fillable que definimos no Model 'Cliente.php' entra em ação aqui,
        // permitindo que $request->all() preencha os campos.
        Cliente::create($request->all());

        // 3. Redirecionamento
        // Redireciona o usuário de volta para a página 'index' (a lista)
        // com uma "flash message" de sucesso.
        return redirect()->route('clientes.index')
                         ->with('sucesso', 'Cliente cadastrado com sucesso!');
    }
    public function show($id)
    {
        // (Deixe vazio por enquanto)
    }

    /**
     * Show the form for editing the specified resource.
     * (Mostrar o formulário de edição)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    // 1. Encontra o cliente no banco de dados pelo ID
    //    (findOrFail falha com um 404 se o ID não existir)
    $cliente = Cliente::findOrFail($id); 

    // 2. Retorna a view de edição e passa o cliente encontrado para ela
    return view('clientes.edit', ['cliente' => $cliente]);
}

    /**
     * Update the specified resource in storage.
     * (Atualizar um cliente no banco)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    // 1. Encontra o cliente que queremos editar
    $cliente = Cliente::findOrFail($id);

    // 2. Validação (é parecida com a do 'store', mas com uma exceção)
    $request->validate([
        'nome' => 'required|string|max:255',

        // Regra de 'unique' especial para atualização:
        // O email deve ser único, IGNORANDO o ID deste cliente.
        // (Senão, ele falharia em si mesmo se o email não for mudado)
        'email' => [
            'nullable',
            'email',
            Rule::unique('clientes')->ignore($cliente->id),
        ],

        'telefone' => 'nullable|string|max:20',
        'data_nascimento' => 'nullable|date',
        'observacoes' => 'nullable|string',
    ]);

    // 3. Atualiza os dados do cliente
    $cliente->update($request->all());

    // 4. Redireciona de volta para a lista (index) com uma mensagem
    return redirect()->route('clientes.index')
                     ->with('sucesso', 'Cliente atualizado com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     * (Excluir um cliente do banco)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    // 1. Encontra o cliente
    $cliente = Cliente::findOrFail($id);

    // 2. Exclui o cliente do banco de dados
    $cliente->delete();

    // 3. Redireciona de volta para a lista com uma mensagem
    return redirect()->route('clientes.index')
                     ->with('sucesso', 'Cliente excluído com sucesso!');
}
}
