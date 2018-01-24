<?php foreach($sucursales as $suckey => $sucval){?>
	<tr>
		<td><?= $sucval["Sucursal"]?></td>
		<td>
			<div class="btn-group">
				<input type="hidden" value="<?= $sucval["Id_Referencia"]?>" class="Id_Cliente">
				<button class="btn btn-primary btn-edit-reg-suc">
					<i class="fa fa-pencil"></i>
				</button>
				<input type="hidden" value="<?= $sucval["Id_Direccion"]?>" class="Id_Direccion">
				<button class="btn btn-danger delet-suc">
					<i class="fa fa-trash"></i>
				</button>			
			</div>				
		</td>
	</tr>
<?php }?>

<?php if(sizeof($sucursales) == 0){?>
	<tr>
		<td colspan="2" style="text-align: center;">No hay sucursales registradas con este cliente</td>
	</tr>
<?php }?>