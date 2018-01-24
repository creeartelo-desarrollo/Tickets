<form class="form-horizontal" id="form-crud" action="<?= base_url('tickets/guardaTicket') ?>">
 <?php if(isset($ticket) && $ticket["Status"] > 2){?>
   <label class="error">El ticket ya está siendo antedido, hay campos que ya no se pueden modificar</label>
 <?php } ?>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Fecha</label>
    <div class="col-sm-9">
      <?php if(isset($ticket)){ ?>
        <input type="hidden" value="<?= $ticket['Id_Ticket'] ?>" name="Id_Ticket">
        <input type="text" placeholder="aaaa-mm-dd" class="form-control" name="txtfecha" value="<?= $ticket['Fecha']?>" disabled="disabled">
      <?php }else{ ?>
        <input type="text" placeholder="aaaa-mm-dd" class="form-control" name="txtfecha" value="<?= date('Y-m-d')?>" disabled="disabled">
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Número de Segimiento</label>
    <div class="col-sm-9">
      <?php if(isset($ticket)){ ?>
        <input type="text" class="form-control" name="txtnoseguimiento" value="<?= $ticket['Num_Seguimiento'] ?>">
      <?php }else{ ?>
        <input type="text" class="form-control" name="txtnoseguimiento" value="<?= $nuevo_numero ?>">
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Ticket</label>
    <div class="col-sm-9">
      <?php if(isset($ticket)){ ?>
        <input type="text" class="form-control" name="txtticket" value="<?= $ticket['Ticket'] ?>">
      <?php }else{ ?>
        <input type="text" class="form-control" name="txtticket">
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Empresa</label>
    <div class="col-sm-9">
      <?php if(isset($ticket) && $ticket["Status"] > 2){?>
        <input type="hidden" name="cmbcliente" value="<?= $ticket['Id_Cliente'] ?>">
        <input type="text" disabled="disabled" value="<?= $ticket['Razon_Social'] ?>" class="form-control">
      <?php }else{ ?>
        <select class="form-control" name="cmbcliente">
          <?php foreach($clientes as $ckey => $cval){?>
             <?php if(isset($ticket) && $ticket["Id_Cliente"] == $cval["Id_Cliente"]){ ?>
              <option value="<?= $cval['Id_Cliente'] ?>" selected><?= $cval["Codigo"] . " - " . $cval["Razon_Social"] ?></option>
             <?php }else{ ?>
              <option value="<?= $cval['Id_Cliente'] ?>"><?= $cval["Codigo"] . " - " . $cval["Razon_Social"] ?></option>
             <?php } ?>
          <?php } ?>
        </select>
       <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Sucursal</label>
    <div class="col-sm-9">
      <?php if(isset($ticket) && $ticket["Status"] > 2){?>
        <input type="hidden" name="cmbdireccion" value="<?= $ticket['Id_Direccion'] ?>">
        <input type="text" disabled="disabled" value="<?= $ticket['Sucursal'] ?>" class="form-control">
      <?php }else{ ?>
        <select class="form-control" name="cmbdireccion">
          <?php foreach($direcciones as $dkey => $dval){?>
            <?php if(isset($ticket) && $ticket["Id_Direccion"] == $dval["Id_Direccion"]){ ?>
              <option value="<?= $dval['Id_Direccion'] ?>" selected><?= $dval["Sucursal"] ?></option>
            <?php }else{ ?>
              <option value="<?= $dval['Id_Direccion'] ?>"><?= $dval["Sucursal"] ?></option>
            <?php } ?>
          <?php } ?>
        </select>
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Dirección</label>
    <div class="col-sm-9">
      <textarea class="form-control" disabled="disabled" id="direccion">
        <?php if(isset($ticket)){ echo $ticket["Direccion"];}?>
      </textarea>
    </div>
  </div>
  
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Descripción del Trabajo</label>
    <div class="col-sm-9">
      <?php if(isset($ticket)){ ?>
        <textarea class="form-control" name="txadescripcion"><?= $ticket["Descripcion"] ?></textarea>
      <?php }else{ ?>
        <textarea class="form-control" name="txadescripcion"></textarea>
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Observaciones</label>
    <div class="col-sm-9">
      <?php if(isset($ticket)){ ?>
        <textarea class="form-control" name="txaobservaciones"><?= $ticket["Observaciones"] ?></textarea>
      <?php }else{ ?>
        <textarea class="form-control" name="txaobservaciones"></textarea>
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Asignado a:</label>
    <div class="col-sm-9">
      <?php if(isset($ticket) && $ticket["Status"] > 2){?>
        <input type="hidden" name="cmbasignado" value="<?= $ticket['Id_Usuario'] ?>">
        <input type="text" disabled="disabled" value="<?= $ticket['Usuario'] ?>" class="form-control">
      <?php }else{?>
        <select class="form-control" name="cmbasignado">
          <option value="0">Asignar después</option>
          <?php foreach ($usuarios as $ukey => $uval) { 
            if($uval["Id_Usuario"] == $ticket["Id_Usuario"]){ 
          ?>        
            <option value="<?= $uval['Id_Usuario'] ?>" selected><?= $uval['Nombre'] ?></option>
          <?php }else{?>
            <option value="<?= $uval['Id_Usuario'] ?>"><?= $uval['Nombre'] ?></option>
          <?php } 
          }?>
        </select>
      <?php } ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3 form-control-label">Notificar al usuario:</label>
    <div class="col-sm-9">
      <div class="i-check">
        <input id="optsi" type="radio" checked="" value="1" name="rdonotofica" class="radio-template">
        <label for="optsi">Si</label>
      </div>
      <div class="i-check">
        <input id="optno" type="radio" value="0" name="rdonotofica" class="radio-template">
        <label for="optno">No</label>
      </div>
    </div>
  </div>
  <div class="form-group row">       
    <div class="col-sm-9 offset-sm-3">
      <button class="btn btn-primary">Guardar</button>
    </div>
  </div>
</form>

<?php if(isset($ticket) && $ticket["Status"] == 9){ ?>
  <hr>
  <form class="form-horizontal" action="<?= base_url('tickets/guardaTicketCuadrilla') ?>" id="form-crud-2">
    <input type="hidden" value="<?= $ticket['Id_Ticket'] ?>" name="Id_Ticket">
    <div class="form-group row">
      <label class="col-sm-3 form-control-label">Diagnóstico:</label>
      <div class="col-sm-9">
        <textarea class="form-control" name="txadiagnostico"><?= $diagnostico["Diagnostico"] ?></textarea>
      </div>
    </div>
    
    <div class="form-group row">
      <label class="col-sm-3 form-control-label">Material:</label>
      <div class="col-sm-9">
        <textarea class="form-control" name="txamaterial"><?= $diagnostico["Material"] ?></textarea>
      </div>
    </div>
    
    <div class="form-group row">
      <label class="col-sm-3 form-control-label">Observaciones:</label>
      <div class="col-sm-9">
        <textarea class="form-control" name="txaobservaciones"><?= $finaliza["Observaciones"] ?></textarea>
      </div>
    </div>
    
    <div class="form-group row">       
      <div class="col-sm-9 offset-sm-3">
        <button class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </form>
<?php } ?>