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
        $funcionarios = User::orderBy('name')->get();
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
     * Salvar o novo funcionário
     */
    public function store(Request $request)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        // Criação
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        // Redirecionamento
        return redirect()->route('funcionarios.index')
                         ->with('sucesso', 'Funcionário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // (Não estamos usando)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // (Não estamos usando)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // (Não estamos usando)
    }

    /**
     * A CORREÇÃO ESTÁ AQUI:
     * Excluir o funcionário do banco
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1. Encontra o funcionário
        $funcionario = User::findOrFail($id);

        // 2. Exclui o funcionário
        $funcionario->delete();

        // 3. Redireciona de volta para a lista com uma mensagem
        return redirect()->route('funcionarios.index')
                         ->with('sucesso', 'Funcionário excluído com sucesso!');
    }
}
