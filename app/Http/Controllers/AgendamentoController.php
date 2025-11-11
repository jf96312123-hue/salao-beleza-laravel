<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostra a página principal da agenda/calendário)
     */
    public function index()
    {
        // Carrega as listas para o formulário no modal
        $clientes = Cliente::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = User::orderBy('name')->get();

        return view('agenda.index', [
            'clientes' => $clientes,
            'servicos' => $servicos,
            'funcionarios' => $funcionarios,
        ]);
    }

    public function create()
    {
        // Não usado.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Validação dos dados
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'user_id' => 'required|exists:users,id',
                'servico_id' => 'required|exists:servicos,id',
                'data_hora_inicio' => 'required|date_format:Y-m-d\TH:i:sP',
                'observacoes' => 'nullable|string',
            ]);

            // 2. VERIFICAÇÃO DE CONFLITO!
            $this->checkAppointmentConflict($request);

            // 3. Buscar duração e calcular data_hora_fim
            $servico = Servico::findOrFail($request->servico_id);
            $dataFim = Carbon::parse($request->data_hora_inicio)->addMinutes($servico->duracao_minutos);

            // 4. Criação do Agendamento
            $agendamento = Agendamento::create([
                'cliente_id' => $request->cliente_id,
                'servico_id' => $request->servico_id,
                'user_id' => $request->user_id,
                'data_hora_inicio' => $request->data_hora_inicio,
                'data_hora_fim' => $dataFim->toDateTimeString(),
                'observacoes' => $request->observacoes,
                'status' => 'agendado',
            ]);

            return response()->json($agendamento, 201);
        } catch (ValidationException $e) {
            // Se a exceção for por validação (incluindo a de conflito), retorna 422 JSON
            return response()->json([
                'message' => 'Erro de validação ou conflito.',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function show($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        return response()->json($agendamento);
    }

    public function edit($id)
    {
        // Não usado.
    }

    /**
     * Update an existing appointment.
     */
    public function update(Request $request, $id)
    {
        try {
            $agendamento = Agendamento::findOrFail($id);

            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'user_id' => 'required|exists:users,id',
                'servico_id' => 'required|exists:servicos,id',
                'data_hora_inicio' => 'required|date_format:Y-m-d\TH:i:sP',
                'observacoes' => 'nullable|string',
            ]);

            $this->checkAppointmentConflict($request, $agendamento->id);

            $servico = Servico::findOrFail($request->servico_id);
            $dataFim = Carbon::parse($request->data_hora_inicio)->addMinutes($servico->duracao_minutos);

            $agendamento->update([
                'cliente_id' => $request->cliente_id,
                'servico_id' => $request->servico_id,
                'user_id' => $request->user_id,
                'data_hora_inicio' => $request->data_hora_inicio,
                'data_hora_fim' => $dataFim->toDateTimeString(),
                'observacoes' => $request->observacoes,
            ]);

            return response()->json($agendamento);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação ou conflito.',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();
        return response()->json([], 204);
    }

    /**
     * Busca os eventos para o FullCalendar (rota de API), com filtro opcional.
     */
    public function getAppointments(Request $request)
    {
        // 1. Inicia a query com os relacionamentos. 
        // Mantive 'funcionario' para compatibilidade com o restante do seu código.
        $query = Agendamento::with('cliente', 'funcionario', 'servico');

        // NOVO: Lógica de Filtro pela lupa (parâmetro 'search')
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // Filtra agendamentos cujo cliente contenha o termo de pesquisa
            $query->whereHas('cliente', function ($q) use ($searchTerm) {
                // Usando ILIKE para pesquisa case-insensitive (Pode ser 'LIKE' para MySQL)
                $q->where('nome', 'ILIKE', '%' . $searchTerm . '%');
            })
                // Opcional: Permite buscar também pelo nome do Serviço
                ->orWhereHas('servico', function ($q) use ($searchTerm) {
                    $q->where('nome', 'ILIKE', '%' . $searchTerm . '%');
                });
        }
        // Fim da Lógica de Filtro

        $agendamentos = $query->get(); // Executa a query filtrada

        $eventos = $agendamentos->map(function ($agendamento) {

            // Garante o cálculo da duração e fuso horário
            $inicio = \Carbon\Carbon::parse($agendamento->data_hora_inicio);

            // Assumindo que seu Model Servico tem um campo 'duracao_minutos'
            $duracaoEmMinutos = $agendamento->servico ? (int)$agendamento->servico->duracao_minutos : 60;

            $fim = $inicio->copy()->addMinutes($duracaoEmMinutos);

            return [
                'id' => $agendamento->id,
                // Título visível no calendário: Cliente e Serviço
                'title' => $agendamento->cliente->nome . ' - ' . $agendamento->servico->nome,

                // Formato ISO 8601 obrigatório para o FullCalendar
                'start' => $inicio->toIso8601String(),
                'end' => $fim->toIso8601String(),

                // resourceId usa o ID do Funcionário
                'resourceId' => $agendamento->funcionario->id,
            ];
        });

        return response()->json($eventos);
    }

    // ----------------------------------------------------
    // MÉTODO DE REGRA DE NEGÓCIO: CONFLITO
    // ----------------------------------------------------

    /**
     * Verifica se o horário solicitado entra em conflito com agendamentos existentes
     * para o mesmo funcionário.
     */
    private function checkAppointmentConflict(Request $request, $exceptId = null)
    {
        // 1. Obter dados e duração
        $employeeId = $request->input('user_id');
        $servico = Servico::find($request->servico_id);
        $duracao = $servico ? $servico->duracao_minutos : 0;

        // 2. Calcular o início e fim
        $newStart = Carbon::parse($request->input('data_hora_inicio'));
        $newEnd = $newStart->copy()->addMinutes($duracao);

        // 3. Buscar conflitos no banco: A CONDIÇÃO CRÍTICA
        $conflictQuery = Agendamento::where('user_id', $employeeId) // <<--- FILTRO POR FUNCIONÁRIO
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where('data_hora_inicio', '<', $newEnd)
                    ->where('data_hora_fim', '>', $newStart);
            });

        // 4. Ignorar o agendamento atual se for edição
        if ($exceptId) {
            $conflictQuery->where('id', '!=', $exceptId);
        }

        // 5. Executar a checagem
        $conflictingAppointment = $conflictQuery->first();

        // 6. Lançar exceção de validação se houver conflito
        if ($conflictingAppointment) {
            $horaConflito = Carbon::parse($conflictingAppointment->data_hora_inicio)->format('H:i');

            throw ValidationException::withMessages([
                'user_id' => [
                    "O(A) funcionário(a) já possui um agendamento que se sobrepõe ao horário (início às {$horaConflito}h). Por favor, escolha outro profissional ou horário."
                ]
            ]);
        }
    }
}
