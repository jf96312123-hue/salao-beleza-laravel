<?php

namespace App\Http\Controllers;

use App\Models\User; // (Funcionário)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class FuncionarioController extends Controller
{
    /**
     * Mostrar a lista de funcionários
     */
    public function index()
    {
        // Busca todos os usuários (funcionários)
        $funcionarios = User::orderBy('name')->get();
        // Retorna a view 'funcionarios.index'
        return view('funcionarios.index', ['funcionarios' => $funcionarios]);
    }

    /**
     * Mostrar o formulário de criação
     */
    public function create()
    {
        return view('funcionarios.create');
    }

    /**
     * Store a newly created resource in storage.
     * (Salvar o novo funcionário)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validação (Apenas Nome e Telefone)
        $request->validate([
            'name' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20', 
        ]);

        // 2. Criação do Funcionário (User)
        User::create([
            'name' => $request->name,
            'telefone' => $request->telefone,
            
            // --- CAMPOS FALSOS (Obrigatórios pelo banco) ---
            'email' => 'func_'.uniqid().'@salao.local', // Gera um e-mail único falso
            'password' => Hash::make('12345678'), // Define uma senha padrão
        ]);

        // 3. Redirecionamento
        return redirect()->route('funcionarios.index')
                         ->with('sucesso', 'Funcionário cadastrado com sucesso!');
    }

    public function show($id) { /* Não implementado */ }
    public function edit($id) { /* Não implementado */ }
    public function update(Request $request, $id) { /* Não implementado */ }

    /**
     * Excluir o funcionário do banco
     */
    public function destroy($id)
    {
        $funcionario = User::findOrFail($id);
        $funcionario->delete();

        return redirect()->route('funcionarios.index')
                         ->with('sucesso', 'Funcionário excluído com sucesso!');
    }
}