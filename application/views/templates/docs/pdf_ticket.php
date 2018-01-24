<table>
	<tr>
		<td>
			<strong>Fecha:</strong>
			<?= $ticket["Fecha"]?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Núm. Seguimiento:</strong>
			<?= $ticket["Num_Seguimiento"]?>
		</td>
	</tr>
	<?php if($ticket_finaliza){?>
	<tr>
		<td>
			<strong>Orden de Servicio: </strong>
			<?= $ticket_finaliza["Orden_Servicio"]?>
		</td>
	</tr>
	<?php }?>
	<?php if($ticket["Ticket"]){?><tr>
		<td>
			<strong>Ticket: </strong>
			<?= $ticket["Ticket"]?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td>
			<strong>Nombre / Empresa: </strong>
			<?= $ticket["Razon_Social"]?>
		</td>
	</tr>	

	<tr>
		<td>
			<strong>Sucursal: </strong>
			<?= $ticket["Sucursal"]?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Dirección: </strong>
			<?= $ticket["Direccion"]?>
		</td>
	</tr>	
</table>

<br>
<div>	
	<strong>Descripción del trabajo:</strong> <br>
	<?= $ticket["Descripcion"] ?>
</div>

<div>	
	<strong>Diagnóstico:</strong> <br>
	<?= $ticket_diagnostico["Diagnostico"] ?>
</div>

<div>	
	<strong>Evidencia inicial:</strong> <br> <br>
	<?php if($ticket_imagenesi){  ?>
		<table cellpadding="5">
			<tr>
				<?php if(isset($ticket_imagenesi[0])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[0]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesi[1])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[1]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesi[2])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[2]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>
			</tr>
			<?php if(isset($ticket_imagenesi[3])){  ?>
			<tr>
				<?php if(isset($ticket_imagenesi[3])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[3]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesi[4])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[4]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesi[5])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesi[5]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>	
	<?php } ?>	
</div>

<div>	
	<strong>Material utilizado:</strong> <br>
	<?= $ticket_diagnostico["Material"] ?>
</div>

<div>	
	<strong>Evidencia final:</strong> <br> <br>
	<?php if($ticket_imagenesf){  ?>
		<table cellpadding="5">
			<tr>
				<?php if(isset($ticket_imagenesf[0])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[0]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesf[1])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[1]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesf[2])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[2]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>
			</tr>
			<?php if(isset($ticket_imagenesf[3])){  ?>
			<tr>
				<?php if(isset($ticket_imagenesf[3])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[3]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesf[4])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[4]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>

				<?php if(isset($ticket_imagenesf[5])){  ?>
					<td>
						<img src="<?= base_url('ticketsfiles/'.$ticket_imagenesf[4]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
					</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>	
	<?php } ?>
</div>

<div>	
	<strong>Foto del reporte firmado:</strong> <br> <br>
	<?php if(isset($reporte[0])){  ?>
		<img src="<?= base_url('ticketsfiles/'.$reporte[0]['Ruta_Imagen']) ?>" style="width: 200px; border: solid; float: left;">
	<?php } ?>
</div>