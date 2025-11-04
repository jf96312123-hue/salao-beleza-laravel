<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;  
use App\Http\Controllers\ServicoController; 
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\EstoqueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| ...
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('clientes', ClienteController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('agendamentos', AgendamentoController::class);
Route::resource('funcionarios', FuncionarioController::class);
Route::resource('estoques', EstoqueController::class);