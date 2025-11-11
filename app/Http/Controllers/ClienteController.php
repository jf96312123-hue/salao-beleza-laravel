<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necessário para usar consultas mais complexas, se precisar
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Importação correta do Carbon

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostra a lista de clientes, com filtro opcional para aniversariantes)
     */
    public function index(Request $request)
    {
        // 1. INICIALIZAÇÃO SEGURA DA VARIÁVEL DE FILTRO
        $filtroAtivo = $request->filtro ?? null;
        
        // 2. Inicia a consulta
        $query = Cliente::orderBy('nome');

        // 3. Lógica de Filtro para ANIVERSARIANTES
        if ($filtroAtivo == 'aniversariantes') {
            $mesAtual = Carbon::now()->month;
            
            // Filtra a consulta para incluir apenas clientes cujo MÊS seja o mês atual
            $query->whereMonth('data_nascimento', $mesAtual);
        }

        // 4. Executa a consulta (com ou sem filtro)
        $clientes = $query->get();

        // 5. Retorna a view
        return view('clientes.index', [
            'clientes' => $clientes,
            'filtroAtivo' => $filtroAtivo, // Passa a variável segura
        ]);
    }

    /**
     * Mostra os clientes que fazem aniversário no mês atual.
     */
    public function aniversariantes()
    {
        $mesAtual = Carbon::now()->month;
        
        $aniversariantes = Cliente::whereMonth('data_nascimento', $mesAtual)
            ->orderBy('data_nascimento', 'asc')
            ->get();

        return view('clientes.aniversariantes', [
            'aniversariantes' => $aniversariantes,
            'nomeDoMes' => Carbon::now()->translatedFormat('F'), 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes', 
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
                         ->with('sucesso', 'Cliente cadastrado com sucesso!');
    }
    
    public function show($id)
    {
        // Não implementado.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', ['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id,
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('sucesso', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('sucesso', 'Cliente excluído com sucesso!');
    }
}