<form class="form-horizontal" id="form-crud-suc" action="<?= base_url('clientes/guardaSucursal') ?>">
	<input type="hidden" name="Id_Cliente" value="<?= $Id_Cliente?>">
	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Sucursal</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="hidden" name="Id_Direccion" value="<?= $sucursal['Id_Direccion']?>">
				<input type="text" class="form-control" name="txtsucursal" value="<?= $sucursal['Sucursal']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtsucursal">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Calle</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtcalle" value="<?= $sucursal['Calle']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtcalle">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Num. Exterior</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtnum_ext" value="<?= $sucursal['No_Ext']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtnum_ext">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Num. Interior</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtnum_int" value="<?= $sucursal['No_Int']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtnum_int">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Colonia</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtcolonia" value="<?= $sucursal['Colonia']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtcolonia">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Municipio</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtmunicipio" value="<?= $sucursal['Municipio']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtmunicipio">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Estado</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="text" class="form-control" name="txtestado" value="<?= $sucursal['Estado']?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtestado">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Código Postal</label>
		<div class="col-sm-9">
			<?php if(isset($sucursal)){ ?>
				<input type="number" class="form-control" name="txtcp" value="<?= $sucursal['Codigo_Postal']?>" maxlength="5" minlength="5">
			<?php }else{ ?>
				<input type="number" class="form-control" name="txtcp" maxlength="5" minlength="5">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Coordenadas</label>
		<div class="col-sm-9">
			<input id="pac-input" class="form-control" type="text" placeholder="Buscar...">
			<div id="mapa" style="height: 350px"></div>
			<br><br>
			<div class="form-group">
              <div class="input-group"><span class="input-group-addon">Latitud</span>
              	<?php if(isset($sucursal)){ ?>
	                <input type="number" id="latitud" name="txtlatitud" class="form-control" value="<?= $sucursal['Latitud']?>">
    			<?php }else{ ?>
    				<input type="number" id="latitud" name="txtlatitud" class="form-control" value="20.6528405">
    			<?php }?>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group"><span class="input-group-addon">Longitud</span>
              	<?php if(isset($sucursal)){ ?>
	                <input type="number"  id="longitud" name="txtlongitud" class="form-control" value="<?= $sucursal['Longitud']?>">
	            <?php }else{ ?>
	            	<input type="number"  id="longitud" name="txtlongitud" class="form-control" value="-103.2562794">
	            <?php }?>
              </div>
            </div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Status</label>
		<div class="col-sm-9">
			<div class="i-checks">
				<?php if(!isset($sucursal) || $sucursal["Status"] == 1){ ?>
					<input id="optactivo" type="radio" checked="" value="1" name="rdostatus" class="radio-template">	
				<?php }else{?>
			        <input id="optactivo" type="radio" value="1" name="rdostatus" class="radio-template">
			    <?php }?>
		        <label for="optactivo">Activo</label>
		    </div>
		    <div class="i-checks">
		    	<?php if(isset($sucursal) && $sucursal["Status"] == 0){ ?>
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