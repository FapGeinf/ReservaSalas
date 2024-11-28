@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h2>Agendaí</h2>
    </header>

    <section>
        <nav id="sidebar">
            <div id="sidebar_content">
                <div id="user">
                    <img src="" id="user_avatar" alt="avatar" width="200px">
                    <p id="user_infos">
                        <span class="item-description">Usuário</span>
                        <span class="item-description">lorem ipsum</span>
                    </p>
                </div>
                <ul id="side_items">
                    <li class="side-item">
                        <a href="#">
                            <i class="fa-sharp-duotone fa-thin fa-chart-user"></i>
                            <span class="item-descripition">Sala</span>
                        </a>
                    </li>
                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-box"></i>
                            <span class="item-descripition">?</span>
                        </a>
                    </li>
                    <li class="side-item">
                        <a href="#">
                            <i class="fa-duotone fa-solid fa-gear"></i>
                            <span class="item-descripition">Notificação</span>
                        </a>
                    </li>
                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-chart-user"></i>
                            <span class="item-descripition">Configuração</span>
                        </a>
                    </li>
                </ul>
                <button id="open_btn">
                    <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
                </button>
                <div id="logout">
                    <button id="logout_btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="item-description">Logout</span>
                    </button>
                </div>
            </div>
        </nav>

        <div class="content">
            <h1>Bem-vindo ao Sistema de Reservas</h1>
            <a href="{{ route('salas.create') }}" class="btn btn-primary">Cadastrar Nova Sala</a>
            <!-- Outros conteúdos da página home -->
        </div>

        @if($reservas->isEmpty())
            <p>Não há reservas de salas no momento.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Usuário</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->sala->nome }}</td>
                            <td>{{ $reserva->usuario->nome }}</td>
                            <td>{{ $reserva->data }}</td>
                            <td>{{ $reserva->hora }}</td>
                            <td>{{ $reserva->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
</div>
@endsection
