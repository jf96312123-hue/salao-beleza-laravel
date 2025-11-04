<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Nossas colunas personalizadas
            $table->string('nome');
            $table->string('telefone', 20); // Celular/WhatsApp
            $table->string('email')->nullable()->unique(); // Opcional, mas único se preenchido
            $table->date('data_nascimento'); // Data de nascimento
            $table->text('observacoes')->nullable(); // Para notas (alergias, preferências, etc.)

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
        Schema::dropIfExists('clientes');
    }
}
