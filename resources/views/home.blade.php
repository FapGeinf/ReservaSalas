@extends('layouts.app')
@section('content')

<style>
  section {
    width: 80%; /* Ocupar 80% da tela */
    margin: auto; /* Centralizar horizontalmente */
    min-height: 80vh; /* Garantir altura mínima */
    display: flex;
    flex-direction: column;
    justify-content: center; /* Centralizar verticalmente */
    padding: 2rem 0;
  }

  .custom-list {
    list-style-type: none; /* Remove marcadores */
    padding: 0;
    margin: 0;
  }

  .custom-list li {
    background: #f8f9fa; /* Fundo claro */
    margin: 0.5rem 0;
    padding: 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    border: 1px solid #b7b7b7;
    box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .custom-list li:hover {
    transform: translateY(-2px);
    box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.2);
  }

  .custom-list img {
    width: 80px; /* Tamanho da imagem */
    height: 80px;
    object-fit: cover;
    border-radius: 50%; /* Imagem circular */
    margin-right: 1rem;
  }

  .custom-list .info {
    flex: 1;
  }

  .custom-list .info h5 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: bold;
  }

  .custom-list .info p {
    margin: 0;
    color: #6c757d;
  }

  .custom-list button {
    background: #007bff;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.2s;
  }

  .custom-list button:hover {
    background: #0056b3;
  }
</style>

<section>
  <div class="text-center mb-4">
    <h2 class="fw-bold">SALAS DE REUNIÃO</h2>
  </div>

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
</section>

@endsection

