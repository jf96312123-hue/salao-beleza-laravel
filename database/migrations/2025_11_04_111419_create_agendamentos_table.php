<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();

            // --- Nossas Chaves Estrangeiras ---

            // 1. Qual FUNCIONÁRIO (User) vai atender?
            // "Crie um 'user_id' que se refere ao 'id' na tabela 'users'"
            $table->foreignId('user_id')->constrained('users');

            // 2. Qual CLIENTE será atendido?
            // "Crie um 'cliente_id' que se refere ao 'id' na tabela 'clientes'"
            $table->foreignId('cliente_id')->constrained('clientes');
            
            // 3. Qual SERVIÇO será realizado?
            // "Crie um 'servico_id' que se refere ao 'id' na tabela 'servicos'"
            $table->foreignId('servico_id')->constrained('servicos');

            // --- Nossas Colunas de Data e Status ---

            // Quando o agendamento começa
            $table->dateTime('data_hora_inicio');
            
            // Quando o agendamento termina (vamos calcular isso)
            $table->dateTime('data_hora_fim');

            // Status do agendamento
            // ('agendado', 'concluido', 'cancelado')
            $table->string('status', 20)->default('agendado'); 
            
            // Observações específicas deste agendamento
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos');
    }
}
