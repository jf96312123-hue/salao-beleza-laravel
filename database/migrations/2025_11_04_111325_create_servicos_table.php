<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();

            // Nossas colunas personalizadas
            $table->string('nome'); // Ex: "Corte Feminino", "Manicure"
            $table->text('descricao')->nullable(); // Descrição opcional do serviço
            
            // Usamos decimal para dinheiro. 8 dígitos no total, 2 após a vírgula.
            // (Permite valores até 999.999,99)
            $table->decimal('preco', 8, 2); 
            
            // Tempo em minutos que o serviço leva. Ex: 60 (para 1 hora)
            $table->integer('duracao_minutos'); 

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
        Schema::dropIfExists('servicos');
    }
}
