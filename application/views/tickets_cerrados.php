 <div class="content-inner">
  <!-- Page Header-->
  <header class="page-header">
    <div class="container-fluid">
      <h2 class="no-margin-bottom">Tickets Cerrados</h2>
    </div>
  </header>
  <!-- Dashboard Counts Section-->
  <section class ="dashboard-counts no-padding-bottom">
    <div class="container-fluid">
      <div class="row bg-white has-shadow">
        <div class="col-lg-12" id="show-listado">
          <!-- MUESTRA TABLA DE LISTADO -->
          <div class="toolbar-crud">
            <button class="abre-modal btn-round btn-success float-right" title="Descargar tabla" data-toggle="tooltip" data-modal="modal-descarga">
              <i class="fa fa-download"></i>
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
                <th>Fecha</th>
                <th>Num. Seguimiento</th>
                <th>Ticket</th>
                <th>Empresa</th>            
                <th>Usuario</th>
                <th>Alerta</th>
                <th>Tiempo</th>
                <th>Orden Servicio</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Fecha</th>
                <th>Num. Seguimiento</th>
                <th>Ticket</th>
                <th>Empresa</th>              
                <th>Usuario</th>
                <th>Alerta</th>
                <th>Tiempo</th>
                <th>Orden Servicio</th>
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
  

<div class="modal fade" id="modal-descarga">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Descargar tabla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frm-exporta" action="<?= base_url('tickets/validaExcel')?>">          
          <div class="form-group">
            <label>Fecha inicial</label>
            <input type="text" placeholder="yyyy-mm-dd" name="txtfechai" class="form-control" value="<?= $primer_fecha ?>">
          </div>

          <div class="form-group">
            <label>Fecha final</label>
            <input type="text" placeholder="yyyy-mm-dd" name="txtfechaf" class="form-control" value="<?= date("Y-m-d")?>">
          </div>
          
          <div class="form-group">
            <label>Filtrar por:</label>
            <label class="checkbox-inline">
              <input type="checkbox" value="a" checked="checked" name="chkfiltros[]"> Abiertos
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="c" checked="checked" name="chkfiltros[]"> Cerrados
            </label>
          </div>

          <button type="submit" class="btn btn-primary">Descargar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</div>