 <div class="content-inner">
  <!-- Page Header-->
  <header class="page-header">
    <div class="container-fluid">
      <h2 class="no-margin-bottom">Usuarios</h2>
    </div>
  </header>
  <!-- Dashboard Counts Section-->
  <section class ="dashboard-counts no-padding-bottom">
    <div class="container-fluid">
      <div class="row bg-white has-shadow">
        <div class="col-lg-12" id="show-listado">
          <!-- MUESTRA TABLA DE LISTADO -->
          <div class="toolbar-crud">
            <button class="abre-formulario btn-round btn-primary float-right" title="Nuevo" data-toggle="tooltip">
              <i class="fa fa-plus"></i>
            </button>
          </div>
          <div class="toolbar-crud">
            <button class=" refresh-table float-right" data-refresh="data-table" title="Refrescar tabla" data-toggle="tooltip">
              <i class="fa fa-refresh"></i>
            </button>
          </div>
          
          <table id="data-table">
            <thead>
              <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>            
                <th>Status</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>              
                <th>Status</th>
                <th>Opciones</th>
              </tr>
            </tfoot>
          </table>  
        </div>
        <div class="col-lg-12" id="show-formulario" style="display: none;">
          <div class="toolbar-crud">
              <button class="cierra-formulario btn-round btn-primary float-right">
                  <i class="fa fa-chevron-left"></i>
              </button>
          </div>
          <div class="lienzo"></div>
        </div>
      </div>
    </div>
  </section>
  