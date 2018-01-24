<form class="form-horizontal" id="form-crud" action="<?= base_url('usuarios/guardaUsuario') ?>">
	<?php if(isset($usuario)){ ?>
		<input type="hidden" name="Id_Usuario" value="<?= $usuario['Id_Usuario']?>">
	<?php }?>
	<div class="form-group row">
		<label class="col-sm-3 form-control-label">Fotografía</label>
		<div class="col-sm-9">
			<?php if(isset($usuario) && $usuario["Ruta_Imagen"]){ ?>
				<div class="col-md-6 float-left">
					<img src="<?= base_url('usuarioslogos/' . $usuario['Ruta_Imagen'])?>" class="img-fluid">
				</div>
				<div class="col-md-6 float-left">
					<input type="file" class="inputfile" name="fleimagen">
				</div>				
			<?php }else{ ?>
				<div class="col-md-6">
					<input type="file" class="inputfile" name="fleimagen">
				</div>		
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Nombre</label>
		<div class="col-sm-9">
			<?php if(isset($usuario)){ ?>
				<input type="text" class="form-control" name="txtnombre" value="<?= $usuario['Nombre']?>" />
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtnombre">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
    	<label class="col-sm-3 form-control-label requerido">Rol</label>
	    <div class="col-sm-9">
	       <select class="form-control" name="cmbrol">
	          	<?php foreach($roles as $rkey => $rval){?>
	            	<?php if(isset($usuario) && $usuario["Id_Rol"] == $rval["Id_Rol"]){ ?>
	            		<option value="<?= $rval['Id_Rol'] ?>" selected><?= $rval["Nombre"] ?></option>
	            	<?php }else{ ?>
	            		<option value="<?= $rval['Id_Rol'] ?>"><?= $rval["Nombre"] ?></option>
	            	<?php } ?>
	         	 <?php } ?>
	        </select>
	    </div>
  	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Usuario</label>
		<div class="col-sm-9">
			<?php if(isset($usuario)){ ?>
				<input type="text" class="form-control" name="txtusuario" value="<?= $usuario['Usuario'] ?>">
			<?php }else{ ?>
				<input type="text" class="form-control" name="txtusuario">
			<?php }?>
		</div>
	</div>

	<div class="form-group row">
		<?php if(isset($usuario)){ ?>
			<label class="col-sm-3 form-control-label">Contraseña</label>
		<?php }else{ ?>
			<label class="col-sm-3 form-control-label requerido">Contraseña</label>
		<?php }?>
		<div class="col-sm-9">
			<input type="password" class="form-control" name="pswcontrasena" id="contrasena">
		</div>
	</div>

	<div class="form-group row">
		<?php if(isset($usuario)){ ?>
			<label class="col-sm-3 form-control-label">Confirmar contraseña</label>
		<?php }else{ ?>
			<label class="col-sm-3 form-control-label requerido">Confirmar contraseña</label>
		<?php }?>
		<div class="col-sm-9">
			<input type="password" class="form-control" name="pswconf_contrasena">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 form-control-label requerido">Status</label>
		<div class="col-sm-9">
			<div class="i-checks">
				<?php if(!isset($usuario) || $usuario["Status"] == 1){ ?>
					<input id="optactivo" type="radio" checked="" value="1" name="rdostatus" class="radio-template">	
				<?php }else{?>
			        <input id="optactivo" type="radio" value="1" name="rdostatus" class="radio-template">
			    <?php }?>
		        <label for="optactivo">Activo</label>
		    </div>
		    <div class="i-checks">
		    	<?php if(isset($usuario) && $usuario["Status"] == 0){ ?>
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