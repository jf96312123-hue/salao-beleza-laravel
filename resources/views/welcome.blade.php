@extends('layouts.app')

@section('title', 'Bem-Vindo')

@section('content')

    <div style="text-align: center; margin-top: 50px;">
        <h1 style="font-size: 40px; color: #343a40;">
            Bem-Vindo ao Sistema do Salão!
        </h1>
        <p style="font-size: 18px; color: #6c757d;">
            Use a barra de navegação superior para acessar:
        </p>
        <div style="margin-top: 30px;">
            <a href="{{ url('/agendamentos') }}" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                Abrir a Agenda
            </a>
        </div>
    </div>

@endsection