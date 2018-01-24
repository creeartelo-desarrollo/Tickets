 <div class="content-inner">
  <!-- Page Header-->
  <header class="page-header">
    <div class="container-fluid">
      <h2 class="no-margin-bottom">Inicio</h2>
    </div>
  </header>
  <!-- Dashboard Counts Section-->
  <section class ="dashboard-counts no-padding-bottom">
    <div class="container-fluid">
      <div class="row bg-white has-shadow">
        <!-- Item -->
        <div class="col-xl-3 col-sm-6">
          <div class="item d-flex align-items-center">
            <div class="icon bg-violet"><i class="fa fa-ticket"></i></div>
            <div class="title"><span>Tickets<br>Abiertos</span>
            </div>
            <div class="number"><strong><?= $countticketsa ?></strong></div>
          </div>
        </div>
      </div>
    </div>
  </section>

   <!-- Dashboard Counts Section-->
  <section class ="dashboard-counts no-padding-bottom">
    <div class="container-fluid">
      <div class="row bg-white has-shadow">
        
        <div class="col-lg-4">
          <select class="form-control" id="cmbpines">
            <optgroup label="Tickets">
              <option value="1">Tickets abiertos</option>
              <option value="2">Tickets sin atender</option>
              <option value="3">Tickets en proceso</option>
            </optgroup>
            <optgroup label="Clientes">
              <option value="4">Sucursales Activas</option>
              <option value="5">Sucursales Inactivas</option>
            </optgroup>
            <optgroup label="Cuadrillas">
              <option value="6">Última ubicación de las cuadrillas</option>
            </optgroup>
          </select>
        </div>
        
        <div class="col-lg-12">
          <div id="mapa" style="width: 100%; height: 500px">
            
          </div>
        </div>
      </div>
    </div>
  </section>
  