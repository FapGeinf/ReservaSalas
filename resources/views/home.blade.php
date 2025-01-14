@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<section>
  <div class="form-wrapper p-30">
    <!-- class="custom__form_create" -->
    <div class="custom__form_create">
      <div class="row">

        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="card border">

            <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
              <img class="fit-image" src="{{ asset('img/auditorio.png') }}" alt="">
            </div>

            <div class="card-body card-fofinho">
              <div class="title-teste text-center d-flex flex-column">
                <span>Local</span>
                <h3 class="fw-bold">AUDIT. TAUATÓ</h3>
                <span class="mt-3" style="color: #969696; font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis corrupti quia necessitatibus repellat ad voluptates?</span>
              </div>
            </div>

            <div class="mx-auto py-3">
              <button class="button-68">Reservar</button>
            </div>
            

            <div class="bg__card_pattern w-100 mx-auto p-1 mb-0">
              <div class="d-flex justify-content-center">

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="card border">

            <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
              <img class="fit-image" src="{{ asset('img/gabinete.png') }}" alt="">
            </div>

            <div class="card-body card-fofinho">
              <div class="title-teste text-center d-flex flex-column">
                <span>Local</span>
                <h3 class="fw-bold">PRESIDÊNCIA</h3>
                <span class="mt-3" style="color: #969696; font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis corrupti quia necessitatibus repellat ad voluptates?</span>
              </div>
            </div>

            <div class="mx-auto py-3">
              <button class="button-68">Reservar</button>
            </div>
            

            <div class="bg__card_pattern w-100 mx-auto p-1 mb-0">
              <div class="d-flex justify-content-center">

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="card border">

            <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
              <img class="fit-image" src="{{ asset('img/daf.png') }}" alt="">
            </div>

            <div class="card-body card-fofinho">
              <div class="title-teste text-center d-flex flex-column">
                <span>Local</span>
                <h3 class="fw-bold">DAF/ DITEC</h3>
                <span class="mt-3" style="color: #969696; font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis corrupti quia necessitatibus repellat ad voluptates?</span>
              </div>
            </div>

            <div class="mx-auto py-3">
              <button class="button-68">Reservar</button>
            </div>
            

            <div class="bg__card_pattern w-100 mx-auto p-1 mb-0">
              <div class="d-flex justify-content-center">

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="card border">

            <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
              <img class="fit-image" src="{{ asset('img/atendimento.png') }}" alt="">
            </div>

            <div class="card-body card-fofinho">
              <div class="title-teste text-center d-flex flex-column">
                <span>Local</span>
                <h3 class="fw-bold">PROTOCOLO</h3>
                <span class="mt-3" style="color: #969696; font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis corrupti quia necessitatibus repellat ad voluptates?</span>
              </div>
            </div>

            <div class="mx-auto py-3">
              <button class="button-68">Reservar</button>
            </div>
            

            <div class="bg__card_pattern w-100 mx-auto p-1 mb-0">
              <div class="d-flex justify-content-center">

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center border__right w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>

                <div class="d-flex align-items-center text-center w-100">
                  <div class="d-flex flex-column w-100">
                    <span class="text-light fw-bold" style="font-size: 25px;">30</span>
                    <span class="text-light w-100">RESERVAS</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <hr>

      <div class="row mt-4">
        <div class="col-12">
          <table id="example" class="display" style="width:100%">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Posição</th>
                <th>Escritório</th>
                <th>Idade</th>
                <th>Data de Início</th>
                <th>Salário</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
              </tr>
              <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011/07/25</td>
                <td>$170,750</td>
              </tr>
              <!-- Adicione mais linhas conforme necessário -->
            </tbody>
          </table>
        </div>
      </div>  
    </div>  
  </div>

  {{-- <div class="form-wrapper mt-2">
    <div class="custom__form_create">
     
    </div>
  </div> --}}
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>

@endsection

