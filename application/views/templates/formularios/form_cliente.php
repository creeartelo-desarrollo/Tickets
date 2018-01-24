<form class="form-horizontal" id="form-crud" action="<?= base_url('clientes/guardaCliente') ?>">
	<?php if(isset($cliente)){ ?>
		<input type="hidden" name="Id_Cliente" value="<?= $cliente['Id_Cliente']?>">
	<?php }?>
	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Logo</label>
		<div class="col-sm-9">
			<?php if(isset($cliente) && $cliente["Logo"]){ ?>
				<input type="hidden" name="Id_Cliente" value="<?= $cliente['Id_Cliente']?>">
				<div class="col-md-6 float-left">
					<img src="<?= base_url('clienteslogos/' . $cliente['Logo'])?>" class="img-fluid">
				</div>
				<div class="col-md-6 float-left">
					<input type="file" class="inputfile" name="flelogo">
				</div>				
			<?php }else{ ?>
				<div class="col-md-6">
					<input type="file" class="inputfile" name="flelogo">
				</div>		
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Código</label>
		<div class="col-sm-9">
			<?php if(isset($cliente)){ ?>
				<input type="text" class="form-control" name="txtcodigo" value="<?= $cliente['Codigo']?>" />
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtcodigo">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Razón Social</label>
		<div class="col-sm-9">
			<?php if(isset($cliente)){ ?>
				<input type="text" class="form-control" name="txtrazon_social" value="<?= $cliente['Razon_Social'] ?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtrazon_social">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Status</label>
		<div class="col-sm-9">
			<div class="i-checks">
				<?php if(!isset($cliente) || $cliente["Status"] == 1){ ?>
					<input id="optactivo" type="radio" checked="" value="1" name="rdostatus" class="radio-template">	
				<?php }else{?>
			        <input id="optactivo" type="radio" value="1" name="rdostatus" class="radio-template">
			    <?php }?>
		        <label for="optactivo">Activo</label>
		    </div>
		    <div class="i-checks">
		    	<?php if(isset($cliente) && $cliente["Status"] == 0){ ?>
					<input id="optinactivo" type="radio" checked="" value="0" name="rdostatus" class="radio-template">
				<?php }else{?>
		    		<input id="optinactivo" type="radio" value="0" name="rdostatus" class="radio-template">
		    	<?php }?>
		      	<label for="optinactivo">Inactivo</label>
		    </div>
		</div>
	</div>

	<div class="form-group row">       
		<div class="col-sm-9 offset-sm-3">
			<button class="btn btn-primary">Guardar</button>
		</div>
  	</div>
</form>

<?php if(isset($cliente)){ ?>
	<div class="col-lg-12">
		<hr>
		<div class="toolbar-crud">
	      <button class="abre-formulario-suc btn-round btn-primary float-right" title="Nuevo" data-toggle="tooltip">
	      	<input type="hidden" class="Id_Cliente" value="<?= $cliente['Id_Cliente']?>">
	        <i class="fa fa-plus"></i>
	      </button>
	    </div>
		<h2>Sucursales</h2>

		<div class="toolbar-crud">
	      <button class="refresh-tablesuc float-right" title="Refrescar tabla" data-toggle="tooltip">
			<input type="hidden" class="Id_Cliente" value="<?= $cliente['Id_Cliente']?>">
	        <i class="fa fa-refresh"></i>
	      </button>
	    </div>

		<table class="table table-striped table-hover" id="tblsucursales">
			<thead>
				<tr>
					<th>Sucursal</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>		
		</table>	
	</div>
<?php }?>


