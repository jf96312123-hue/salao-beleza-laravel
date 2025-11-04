<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importe o BelongsTo

class Agendamento extends Model
{
    use HasFactory;

    /**
     * Define as colunas que podem ser preenchidas em massa.
     */
    protected $fillable = [
        'user_id',
        'cliente_id',
        'servico_id',
        'data_hora_inicio',
        'data_hora_fim',
        'status',
        'observacoes',
    ];

    /**
     * Define o relacionamento: Um Agendamento pertence a um Funcionário (User).
     */
    public function funcionario(): BelongsTo
    {
        // 'user_id' é a chave estrangeira, 'id' é a chave local do User
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Define o relacionamento: Um Agendamento pertence a um Cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Define o relacionamento: Um Agendamento pertence a um Serviço.
     */
    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }
}