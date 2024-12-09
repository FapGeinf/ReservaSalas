@extends('layouts.app')
@section('content')

<head>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<section>
  <div class="container-md my-4 fixed-top">
    <div class="row g-3">

      <div class="col-sm-6 col-md-3">
        <div class="card">
          <img src="https://via.placeholder.com/150" class="card-img-top p-3" alt="...">
          <div class="card-body text-center">
            <h5 class="card-title">
              AQUÁRIO
            </h5>

            <button class="btn btn-primary mt-3">
              Agendamento
            </button>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="card">
          <img src="https://via.placeholder.com/80x80" class="card-img-top p-3" alt="...">
          <div class="card-body text-center">
            <h5 class="card-title">
              DAF/DITEC
            </h5>

            <button class="btn btn-primary mt-3">
              Agendamento
            </button>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="card">
          <img src="https://via.placeholder.com/150" class="card-img-top p-3" alt="...">
          <div class="card-body text-center">
            <h5 class="card-title">
              PRESIDÊNCIA
            </h5>

            <button class="btn btn-primary mt-3">
              Agendamento
            </button>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="card">
          <img src="https://via.placeholder.com/150" class="card-img-top p-3" alt="...">
          <div class="card-body text-center">
            <h5 class="card-title">
              AUDITÓRIO TAUATÓ
            </h5>

            <button class="btn btn-primary mt-3">
              Agendamento
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- <section>
  <div>
    <ul class="custom-list">
      <li>
        <img src="{{ asset('img/auditorio.png') }}" alt="Imagem do Auditório Tauató">
        <div class="info">
          <h5>Auditório Tauató</h5>
          <p>Espaço amplo para apresentações e eventos.</p>
        </div>
        <button data-toggle="modal" data-target="#agendarModal">Informações</button>
      </li>
      <li>
        <img src="{{ asset('img/atendimento.png') }}" alt="Atendimento aos Pesquisadores">
        <div class="info">
          <h5>Atendimento aos Pesquisadores</h5>
          <p>Espaço dedicado para reuniões com pesquisadores.</p>
        </div>
        <button data-toggle="modal" data-target="#agendarModal">Informações</button>
      </li>
      <li>
        <img src="{{ asset('img/daf.png') }}" alt="DAF/DITEC">
        <div class="info">
          <h5>DAF/DITEC</h5>
          <p>Sala para encontros técnicos e administrativos.</p>
        </div>
        <button data-toggle="modal" data-target="#agendarModal">Informações</button>
      </li>
      <li>
        <img src="{{ asset('img/atendimento.png') }}" alt="Atendimento aos Pesquisadores">
        <div class="info">
          <h5>Atendimento aos Pesquisadores</h5>
          <p>Mais um espaço para interações acadêmicas e profissionais.</p>
        </div>
        <button data-toggle="modal" data-target="#agendarModal">Informações</button>
      </li>
    </ul>
  </div>

  <div class="text-center mb-4">
    <h2 class="fw-bold">SALAS DE REUNIÃO</h2>
  </div>

</section> --}}

@endsection

