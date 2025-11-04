<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstoquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();

            // Colunas personalizadas
            $table->string('nome_produto'); // Nome do item em estoque
            $table->integer('quantidade');   // Quantidade de itens em estoque
            $table->decimal('preco_unitario', 8, 2); // Preço de compra unitário (Ex: R$ 50.00)
            $table->date('data_compra');    // Data em que o item foi comprado

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
        Schema::dropIfExists('estoques');
    }
}
