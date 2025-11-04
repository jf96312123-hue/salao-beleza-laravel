<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; // Para manipulação de datas

class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostrar a página principal da agenda/calendário)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. Buscar todas as listas necessárias para os formulários
        $clientes = Cliente::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = User::orderBy('name')->get();

        // 2. Retornar a view e passar as listas para ela
        return view('agenda.index', [
            'clientes' => $clientes,
            'servicos' => $servicos,
            'funcionarios' => $funcionarios,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Não usado, pois usamos um modal na view index.
    }

    /**
     * Store a newly created resource in storage.
     * (Salvar o novo agendamento no banco)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servico_id' => 'required|exists:servicos,id',
            'user_id' => 'required|exists:users,id',
            // Validação rigorosa do formato ISO 8601 do FullCalendar
            'data_hora_inicio' => 'required|date_format:Y-m-d\TH:i:sP', 
            'observacoes' => 'nullable|string',
        ]);

        // 2. Buscar o serviço para saber a duração
        $servico = Servico::findOrFail($request->servico_id);
        $duracao = $servico->duracao_minutos;

        // 3. Calcular a data_hora_fim
        $dataInicio = Carbon::parse($request->data_hora_inicio);
        $dataFim = $dataInicio->copy()->addMinutes($duracao);

        // 4. Criar o agendamento no banco
        $agendamento = Agendamento::create([
            'cliente_id' => $request->cliente_id,
            'servico_id' => $request->servico_id,
            'user_id' => $request->user_id,
            'data_hora_inicio' => $request->data_hora_inicio,
            'data_hora_fim' => $dataFim->toDateTimeString(),
            'observacoes' => $request->observacoes,
            'status' => 'agendado', // Define um status padrão
        ]);

        // 5. Retornar uma resposta JSON
        return response()->json($agendamento, 201); // 201 = Created
    }

    /**
     * Display the specified resource.
     * (Retorna dados de UM agendamento para o modal de edição)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Encontra o agendamento e o retorna como JSON
        $agendamento = Agendamento::findOrFail($id);
        return response()->json($agendamento);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Não usado.
    }

    /**
     * Update the specified resource in storage.
     * (Atualizar um agendamento no banco)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. Encontra o agendamento que queremos editar
        $agendamento = Agendamento::findOrFail($id);

        // 2. Validação dos dados
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servico_id' => 'required|exists:servicos,id',
            'user_id' => 'required|exists:users,id',
            // Validação rigorosa do formato ISO 8601
            'data_hora_inicio' => 'required|date_format:Y-m-d\TH:i:sP', 
            'observacoes' => 'nullable|string',
        ]);

        // 3. Buscar o serviço para recalcular a duração
        $servico = Servico::findOrFail($request->servico_id);
        $duracao = $servico->duracao_minutos;

        // 4. Calcular a data_hora_fim
        $dataInicio = Carbon::parse($request->data_hora_inicio);
        $dataFim = $dataInicio->copy()->addMinutes($duracao);

        // 5. Atualizar o agendamento no banco
        $agendamento->update([
            'cliente_id' => $request->cliente_id,
            'servico_id' => $request->servico_id,
            'user_id' => $request->user_id,
            'data_hora_inicio' => $request->data_hora_inicio,
            'data_hora_fim' => $dataFim->toDateTimeString(),
            'observacoes' => $request->observacoes,
        ]);

        // 6. Retornar a resposta JSON
        return response()->json($agendamento);
    }

    /**
     * Remove the specified resource from storage.
     * (Excluir um agendamento no banco)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1. Encontra o agendamento
        $agendamento = Agendamento::findOrFail($id);

        // 2. Exclui o agendamento
        $agendamento->delete();

        // 3. Retorna uma resposta de sucesso sem conteúdo (204 No Content)
        return response()->json([], 204);
    }

    /**
     * Busca os eventos para o FullCalendar (rota de API).
     */
    public function getEventos()
    {
        // Buscar os agendamentos com os relacionamentos para otimização
        $agendamentos = Agendamento::with('servico', 'cliente')->get();

        // Formatar os dados para o FullCalendar
        $eventosFormatados = $agendamentos->map(function ($agendamento) {
            
            // Define o título do evento
            $titulo = $agendamento->servico->nome; 
            if ($agendamento->cliente) {
                $titulo .= ' - ' . $agendamento->cliente->nome; 
            }

            return [
                'id'    => $agendamento->id,
                'title' => $titulo,
                'start' => $agendamento->data_hora_inicio, 
                'end'   => $agendamento->data_hora_fim,    
                // 'color' => ($agendamento->status == 'agendado' ? '#007bff' : '#dc3545'), // Exemplo de cor
            ];
        });

        // Retornar os dados como JSON
        return response()->json($eventosFormatados);
    }
}