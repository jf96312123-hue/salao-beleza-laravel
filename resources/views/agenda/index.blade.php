@extends('layouts.app')

@section('title', 'Agenda do Salão')

{{-- Injeta o CSS específico do FullCalendar no <head> do layout --}}
@push('styles')
    <style>
        /* Estilos do FullCalendar e posicionamento */
        #calendar {
            max-width: 90%; /* Aumenta o preenchimento da tela */
            margin: 0 auto;
            height: 100%; 
            min-height: 800px; /* Garante altura mínima para aparecer */
        }
        
        /* --- ESTILOS CRÍTICOS PARA CORREÇÃO DE CLIQUE (NOVO) --- */
        .fc-timegrid-event {
            /* Força o evento a ocupar apenas 85% da largura da coluna, liberando a faixa lateral para o clique. */
            width: 85% !important; 
            margin-right: 15% !important; 
            margin-left: 0 !important;
        }
        /* Garante que o conteúdo do evento se ajuste à nova largura */
        .fc-event-main-frame {
            white-space: normal; 
        }
        /* FIM DA CORREÇÃO DE CLIQUE */
        
        /* --- Estilos do Modal --- */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe; 
            margin: 10% auto; 
            padding: 20px; 
            border: 1px solid #888; 
            width: 80%; 
            max-width: 500px; 
            border-radius: 8px;
        }
        .modal-close {
            color: #aaa; 
            float: right; 
            font-size: 28px; 
            font-weight: bold; 
            cursor: pointer;
        }
        .modal-form div {
            margin-bottom: 15px;
        }
        .modal-form label {
            display: block;
            margin-bottom: 5px;
        }
        .modal-form select,
        .modal-form textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .modal-footer {
            text-align: right; 
            margin-top: 20px;
        }
        .modal-btn {
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px; 
            color: white; 
            cursor: pointer;
        }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; }
        .btn-danger { background-color: #dc3545; }
    </style>
@endpush

@section('content')

    <h1 class="h1-custom">Agenda do Salão</h1>

    <div style="width: 100%; max-width: 1100px; margin-bottom: 20px;">
        <label for="agendaSearch" style="font-weight: bold; margin-bottom: 5px;">
            🔍 Pesquisar Agendamento por Cliente:
        </label>
        <input type="text" id="agendaSearch" onkeyup="filterAgenda()" placeholder="Digite o nome do cliente..." 
               style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    <div id='calendar'></div>

    <div id="agendamentoModal" class="modal">
        <div class="modal-content">
            
            <span id="fecharModal" class="modal-close">&times;</span>
            
            <h2 id="modalTitle">Novo Agendamento</h2>

            <form id="formAgendamento" class="modal-form">
                
                <input type="hidden" id="agendamento_id" name="agendamento_id">
                <input type="hidden" id="data_hora_inicio" name="data_hora_inicio">
                
                <div>
                    <label for="cliente_id">Cliente:</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Selecione um cliente...</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="servico_id">Serviço:</label>
                    <select id="servico_id" name="servico_id" required>
                        <option value="">Selecione um serviço...</option>
                        @foreach ($servicos as $servico)
                            <option value="{{ $servico->id }}">{{ $servico->nome }} (R$ {{ $servico->preco }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user_id">Funcionário:</label>
                    <select id="user_id" name="user_id" required>
                        <option value="">Selecione um funcionário...</option>
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}">{{ $funcionario->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes" rows="3"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnCancelar" class="modal-btn btn-secondary">Cancelar</button>
                    <button type="submit" id="btnSalvar" class="modal-btn btn-primary">Salvar</button>
                    <button type="button" id="btnExcluir" class="modal-btn btn-danger" style="display: none;">Excluir</button>
                </div>

            </form>
        </div>
    </div>
    @endsection

{{-- Injeta o JavaScript e a inicialização do calendário no final do <body> --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // --- Referências ao Modal ---
            var modal = document.getElementById('agendamentoModal');
            var modalTitle = document.getElementById('modalTitle');
            var fecharModal = document.getElementById('fecharModal');
            var btnCancelar = document.getElementById('btnCancelar');
            var formAgendamento = document.getElementById('formAgendamento');
            var inputDataHoraInicio = document.getElementById('data_hora_inicio');
            var inputAgendamentoId = document.getElementById('agendamento_id');
            var btnExcluir = document.getElementById('btnExcluir');
            var btnSalvar = document.getElementById('btnSalvar');

            // --- Funções de Abrir/Fechar Modal ---
            function abrirModal(data) {
                inputDataHoraInicio.value = data; 
                formAgendamento.reset();
                inputAgendamentoId.value = '';
                btnExcluir.style.display = 'none';
                btnSalvar.innerText = 'Salvar';
                modalTitle.innerText = 'Novo Agendamento';
                modal.style.display = 'block';
            }

            function fecharModalFunction() {
                modal.style.display = 'none';
            }

            // --- Eventos de Fechar Modal ---
            fecharModal.onclick = fecharModalFunction;
            btnCancelar.onclick = fecharModalFunction;
            window.onclick = function(event) {
                if (event.target == modal) {
                    fecharModalFunction();
                }
            }
            
            // NOVO: Função para disparar a pesquisa no FullCalendar
            function filterAgenda() {
                var searchTerm = document.getElementById('agendaSearch').value;
                
                if (calendar) {
                     // CRÍTICO: Anexa o termo de pesquisa como um parâmetro 'search' na URL
                     calendar.setOption('events', '/api/agendamentos/eventos?search=' + encodeURIComponent(searchTerm));
                     calendar.refetchEvents(); // Força o FullCalendar a recarregar a URL com o filtro
                }
            }
            window.filterAgenda = filterAgenda; // Torna a função acessível globalmente (onkeyup)


            // --- Evento de SUBMIT (Salvar/Atualizar) ---
            formAgendamento.addEventListener('submit', function(e) {
                
                e.preventDefault(); 
                var formData = new FormData(formAgendamento);
                var dados = {};
                formData.forEach((value, key) => dados[key] = value);

                // Lógica de UPDATE/STORE
                var agendamentoID = inputAgendamentoId.value;
                var url;
                var metodo;

                if (agendamentoID) {
                    url = `/agendamentos/${agendamentoID}`; 
                    metodo = 'PUT'; 
                } else {
                    url = "{{ route('agendamentos.store') }}"; 
                    metodo = 'POST';
                }

                fetch(url, {
                    method: metodo, 
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(dados)
                })
                .then(response => {
                    if (response.status === 422) { 
                        return response.json().then(data => {
                            // Tenta pegar o erro do campo 'user_id' (conflito)
                            var errorMsg = data.errors.user_id ? data.errors.user_id[0] : Object.values(data.errors)[0][0];
                            throw new Error(errorMsg);
                        });
                    }
                    if (!response.ok) {
                        throw new Error('Erro ao salvar.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Sucesso!
                    fecharModalFunction(); 
                    calendar.refetchEvents(); // Atualiza o calendário
                    
                    if (agendamentoID) {
                        alert('Agendamento ATUALIZADO com sucesso!');
                    } else {
                        alert('Agendamento SALVO com sucesso!');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert(error.message);
                });
            });


            // --- Evento de EXCLUIR Agendamento --- 
            btnExcluir.addEventListener('click', function() {
                
                var agendamentoID = inputAgendamentoId.value;
                
                if (!agendamentoID) return; 
                
                if (!confirm("Tem certeza que deseja excluir este agendamento?")) {
                    return;
                }
                
                // Envia a requisição DELETE
                fetch(`/agendamentos/${agendamentoID}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.status === 204) {
                        // Sucesso na exclusão (204 No Content)
                        fecharModalFunction(); 
                        calendar.refetchEvents(); // Atualiza o calendário
                        alert('Agendamento excluído com sucesso!');
                    } else {
                        throw new Error('Erro ao excluir o agendamento.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert(error.message);
                });
            }); 


            // --- Configuração do FullCalendar ---
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', 
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' 
                },
                locale: 'pt-br',
                buttonText: { today: 'Hoje', month: 'Mês', week: 'Semana', day: 'Dia' },
                editable: true,     
                selectable: true,
                slotMinTime: '08:00:00', 
                slotMaxTime: '20:00:00', 
                allDaySlot: false,
                navLinks: true, 
                slotEventOverlap: false, // Permite visualização correta de horários simultâneos
                
                eventStartEditable: false, // CORREÇÃO: Impede que clicar em um evento acione a edição
                eventDurationEditable: false, // CORREÇÃO: Impede arrasto/redimensionamento

                // >>>>> CÓDIGO CRÍTICO: RECURSOS (FUNCIONÁRIOS) <<<<<
                resources: {!! json_encode($funcionarios->map(function($f) {
                        return ['id' => $f->id, 'title' => $f->name];
                    })) !!}
                , // Adiciona a vírgula de forma segura após o objeto JSON
                // >>>>> FIM DO CÓDIGO CRÍTICO <<<<<

                // Onde ele busca os agendamentos (Eventos)
                events: '/api/agendamentos/eventos', 

                // Ação de 'select' (clicar em horário vago)
                select: function(info) {
                    abrirModal(info.startStr);
                },

                // Ação de clicar num evento existente
                eventClick: function(info) {
                    
                    var id = info.event.id;
                    
                    fetch(`/agendamentos/${id}`) 
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Não foi possível carregar os dados do agendamento.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Preenche o formulário do modal
                            modalTitle.innerText = 'Editar Agendamento';
                            formAgendamento.reset(); 
                            
                            inputAgendamentoId.value = data.id; 
                            inputDataHoraInicio.value = data.data_hora_inicio;
                            document.getElementById('cliente_id').value = data.cliente_id;
                            document.getElementById('servico_id').value = data.servico_id;
                            document.getElementById('user_id').value = data.user_id;
                            document.getElementById('observacoes').value = data.observacoes || '';

                            btnSalvar.innerText = 'Salvar Alterações';
                            btnExcluir.style.display = 'inline-block'; 

                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert(error.message);
                        });
                } 
            });

            // Renderiza o calendário
            calendar.render();
            // Garante que o calendário seja redimensionado corretamente no carregamento
            calendar.updateSize(); 
        });
    </script>
@endpush