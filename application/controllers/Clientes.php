<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Clientes_model");
	}

	/**
	 * CARGAS VISTAS DEL MÓDULO
	 */
	public function index()
	{
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$resources = array(
				"session"       => $session,
				"modulojs"      => "clientes.js",	
			);

			$this->load->view("templates/header",$resources);
			$this->load->view("templates/sidebar",$resources);
			$this->load->view("clientes",$resources);
			$this->load->view("templates/footer",$resources);
		}else{
			redirect(base_url());
		}
	}

	/**
	  * LISTADO DE CLIENTES
	  */
	public function listarClientes()
	{
		$draw = intval($this->input->post("draw"));
		// Registros a saltar
        $skip = $this->input->post("start");
        // Numero de registros a tomar
        $take = $this->input->post("length");
        // Columnas
        $columns = $this->input->post("columns");
        // Datos del Sort
        $order_data = $this->input->post("order")[0];
        // Nombre de la Columna a Ordenar
        $order_field = $columns[$order_data["column"]]["data"];
        // Dirección del Orden
        $order = $order_data["dir"];
        // Valor de búsqueda
        $busqueda = $this->input->post("search")["value"];
       // Total de registros
        $total =  $this->Clientes_model->COUNT_Clientes();

        if($busqueda){
            $clientes = $this->Clientes_model->CNS_ClientesMatch($skip, $take, $order_field, $order, $busqueda);
            $filtered_rows = sizeof($clientes);
        }else{
            $clientes = $this->Clientes_model->CNS_Clientes($skip, $take, $order_field, $order);
            $filtered_rows = $total;
        }

        $data = array(
			"draw"            => $draw,
			"recordsTotal"    => $total,
			"recordsFiltered" => $filtered_rows,
			"data"            => $clientes
        );        
        echo json_encode($data);
	}

	/** 
 	  *	 MUESTRA FORMULARIO NUEVO / EDICIÓN	
	  */
	public function muestraFormulario(){
		$Id_Cliente = $this->input->post("Id_Cliente");
		#	Si se recibe el Id_Cliente es un UPDATE
		$data = array();
		if(is_numeric($Id_Cliente)){
			$data = array("cliente"    => $this->Clientes_model->CNS_ClienteByID($Id_Cliente));
		}

		#	Carga vista
		$this->load->view("templates/formularios/form_cliente",$data);
	}

	/**
	 * MUESTRA CUERPO DE LA TABLA DE LAS SUCURSALES
	 * DE UN CLIENTE
	 */
	public function muestraSucursales(){
		$Id_Cliente = $this->input->post("Id_Cliente");
		$data["sucursales"] = $this->Clientes_model->CNS_SucursalesCliente($Id_Cliente);
		$this->load->view("templates/tabla_sucursales", $data);
	}

	/**
	 * INSERTA / ACUTALIZA CLIENTE
	 * SI RECIBE Id_Cliente => Actualiza
	 * DE LO CONTRARIO INSERTA
	 */
	public function guardaCliente(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");
		#	Id_Cliente para saber si es UPDATE
		$Id_Cliente = $this->input->post("Id_Cliente");

		#	Array de validaciones
		$rules = array(
			array("field" => "txtcodigo", "label" => "Código", "rules" => "required|max_length[20]|is_unique[Clientes.Codigo.$Id_Cliente]|html_escape|trim"),
			array("field" => "txtrazon_social", "label" => "Razón Social", "rules" => "required|max_length[200]|is_unique[Clientes.Razon_Social.$Id_Cliente]|html_escape|trim"),
			array("field" => "rdostatus", "label" => "Status", "rules" => "required|html_escape|trim|integer"),
		);

		#	Array de validaciones del file
		$rules_file = array(
				"upload_path"   => "./clienteslogos",
				"allowed_types" => "jpg|jpeg|png",
				"max_size"      => 2048,
				"encrypt_name"  => true
			);
		
		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);
		$this->load->library("upload",$rules_file);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}

		#	Datos a guardar
		$dataarray = array(
			"Codigo"          => $this->input->post("txtcodigo"),
			"Razon_Social"    => $this->input->post("txtrazon_social"),
			"Status"          => intval($this->input->post("rdostatus")),
			"Fecha_Actualiza" => date("Y-m-d H:i:s"),
		);

		#	Si se cargó una imagen se procesa
		if($_FILES["flelogo"]["size"] > 0){
			#	Si se subió la imagen
			if($this->upload->do_upload("flelogo")){
				#	Si se recibe el Id_Cliente (UPDATE), se borra la imagen anterior
				if(is_numeric($Id_Cliente)){
					$cliente = $this->Clientes_model->CNS_ClienteByID($Id_Cliente);
					$rutaborrar = "./clienteslogos/".$cliente["Logo"];
					if(is_file($rutaborrar) && file_exists($rutaborrar)){
						unlink($rutaborrar);
					}
				}

				$dataarray["Logo"] = $this->upload->data("file_name");			
			}else{
				echo json_encode(array("head" => "_er:","body" => $this->upload->display_errors(" ","\n")));
				exit();
			}		
		}

		#	Si se recibe Id_Cliente: Acutaliza en base de datos
		if(is_numeric($Id_Cliente)){
			$ar = $this->Clientes_model->UPD_Cliente($Id_Cliente,$dataarray);
			if($ar){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha agregado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}else{
		#	Si no se recibe Id_Cliente: Inserta en base de datos
			$dataarray["Fecha_Alta"] = date("Y-m-d H:i:s");
			$last = $this->Clientes_model->INS_Cliente($dataarray);
			if($last){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha agregado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}
	}

	/**
	 * ELIMINA UN CLIENTE
	 */
	public function eliminarCliente(){
		#	Id_Cliente para eliminar
		$Id_Cliente = $this->input->post("Id_Cliente");

		#	Borra logo del cliente
		$cliente = $this->Clientes_model->CNS_ClienteByID($Id_Cliente);
		$rutaborrar = "./clienteslogos/".$cliente["Logo"];
		if(is_file($rutaborrar) && file_exists($rutaborrar)){
			unlink($rutaborrar);
		}

		$ard = $this->Clientes_model->DEL_DireccionByCliente($Id_Cliente);
		$ar  = $this->Clientes_model->DEL_Cliente($Id_Cliente);
		if($ar){
			echo json_encode(array("head" => "_ok:","body" => "Excelete hemos eliminado tu registro"));
		}else{
			echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al eliminar tu registro, intentalo nuevamente"));
		}	
	}

	/** 
 	  *	 MUESTRA FORMULARIO NUEVO / EDICIÓN	
	  */
	public function muestraFormularioSucursal(){
		$Id_Direccion = $this->input->post("Id_Direccion");
		$Id_Cliente   = $this->input->post("Id_Cliente");

		#	Si se recibe el Id_Direccion es un UPDATE
		$data = array("Id_Cliente" => $Id_Cliente);
		if(is_numeric($Id_Direccion)){
			$data["Id_Direccion"] = $Id_Direccion;
			$data["sucursal"] = $this->Clientes_model->CNS_SucursalByID($Id_Direccion);
		}

		#	Carga vista
		$this->load->view("templates/formularios/form_sucursal",$data);
	}

	/**
	 * INSERTA / ACUTALIZA DIRECCION
	 * SI RECIBE Id_Direccion => Actualiza
	 * DE LO CONTRARIO INSERTA
	 */
	public function guardaSucursal(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");
		#	Id_Direccion para saber si es UPDATE
		$Id_Direccion = $this->input->post("Id_Direccion");

		#	Array de validaciones
		$rules = array(
			array("field" => "txtsucursal", "label" => "Sucursal", "rules" => "required|max_length[50]|html_escape|trim"),
			array("field" => "txtcalle", "label" => "Calle", "rules" => "required|max_length[100]|html_escape|trim"),
			array("field" => "txtnum_ext", "label" => "Num. Exterior", "rules" => "required|max_length[30]|html_escape|trim"),
			array("field" => "txtnum_int", "label" => "Num. Interior", "rules" => "max_length[30]|html_escape|trim"),
			array("field" => "txtcolonia", "label" => "Colonia", "rules" => "required|max_length[100]|html_escape|trim"),
			array("field" => "txtmunicipio", "label" => "Municipio", "rules" => "required|max_length[150]|html_escape|trim"),			
			array("field" => "txtestado", "label" => "Estado", "rules" => "required|max_length[150]|html_escape|trim"),
			array("field" => "txtcp", "label" => "Código Postal", "rules" => "required|integer|exact_length[5]|html_escape|trim"),
			array("field" => "txtlatitud", "label" => "Latitud", "rules" => "required|numeric|html_escape|trim"),
			array("field" => "txtlongitud", "label" => "Longitud", "rules" => "required|numeric|html_escape|trim"),
			array("field" => "rdostatus", "label" => "Status", "rules" => "required|html_escape|trim|integer"),
		);

		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}

		$dataarray = array(
			"Id_Referencia"   => $this->input->post("Id_Cliente"),
			"Sucursal"        => $this->input->post("txtsucursal"),
			"Calle"           => $this->input->post("txtcalle"),
			"No_Ext"          => $this->input->post("txtnum_ext"),
			"No_Int"          => $this->input->post("txtnum_int"),
			"Colonia"         => $this->input->post("txtcolonia"),
			"Municipio"       => $this->input->post("txtmunicipio"),
			"Estado"          => $this->input->post("txtestado"),
			"Codigo_Postal"   => $this->input->post("txtcp"),
			"Latitud"         => $this->input->post("txtlatitud"),
			"Longitud"        => $this->input->post("txtlongitud"),
			"Status"          => intval($this->input->post("rdostatus")),
			"Fecha_Actualiza" => date("Y-m-d H:i:s"),
		);

		#	Si se recibe Id_Direccion: Acutaliza en base de datos
		if(is_numeric($Id_Direccion)){
			$ar = $this->Clientes_model->UPD_Direccion($Id_Direccion,$dataarray);
			if($ar){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha guardado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}else{
		#	Si no se recibe Id_Direccion: Inserta en base de datos
			$dataarray["Fecha_Alta"] = date("Y-m-d H:i:s");
			$last = $this->Clientes_model->INS_Direccion($dataarray);
			if($last){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha guardado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}
	}

	/**
	 * ELIMINA UNA DIRECCION 
	 */
	public function eliminarDireccion(){
		#	Id_Direccion para saber si es UPDATE
		$Id_Direccion = $this->input->post("Id_Direccion");

		$ar = $this->Clientes_model->DEL_Direccion($Id_Direccion);
		if($ar){
			echo json_encode(array("head" => "_ok:","body" => "Excelete hemos eliminado tu registro"));
		}else{
			echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al eliminar tu registro, intentalo nuevamente"));
		}	
	}

}

?>