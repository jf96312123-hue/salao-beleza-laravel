<?php

namespace App\Models;

// Imports de classes necessárias
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- Correção para o erro 'Authenticatable not found'
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Para o relacionamento com Agendamentos

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * A CORREÇÃO ESTÁ AQUI:
     * Os atributos que podem ser preenchidos em massa (via ::create()).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', // <-- A linha que faltava para corrigir o 'MassAssignmentException'
    ];

    /**
     * Os atributos que devem ser ocultados ao serializar (ex: em JSONs).
     * (Padrão do Laravel)
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos (cast).
     * (Padrão do Laravel)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * A função que adicionamos para a Agenda:
     * Define o relacionamento: Um Funcionário (User) pode ter muitos Agendamentos.
     */
    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}