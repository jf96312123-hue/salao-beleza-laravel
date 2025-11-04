<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignable).
     * Essencial para o Estoque::create($request->all()); funcionar.
     * Certifique-se de que os nomes correspondem exatamente às colunas da sua migração.
     */
    protected $fillable = [
        'nome_produto',
        'quantidade',
        'preco_unitario',
        'data_compra',
    ];
}