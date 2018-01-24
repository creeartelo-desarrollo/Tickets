<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Usuarios_model");
	}

	public function index()
	{
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$resources = array(
				"session"       => $session,
				"modulojs"      => "usuarios.js",	
			);

			$this->load->view("templates/header",$resources);
			$this->load->view("templates/sidebar",$resources);
			$this->load->view("usuarios",$resources);
			$this->load->view("templates/footer",$resources);
		}
	}

	/**
	  * LISTADO DE USUARIOS
	  */
	public function listarUsuarios()
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
        $total =  $this->Usuarios_model->COUNT_Usuarios();

        if($busqueda){
            $tickets = $this->Usuarios_model->CNS_UsuariosMatch($skip, $take, $order_field, $order, $busqueda);
            $filtered_rows = sizeof($tickets);
        }else{
            $tickets = $this->Usuarios_model->CNS_Usuarios($skip, $take, $order_field, $order);
            $filtered_rows = $total;
        }

        $data = array(
            "draw" => $draw,
            "recordsTotal" =>  $total,
            "recordsFiltered" =>  $filtered_rows,
            "data" => $tickets   
        );        
        echo json_encode($data);
	}

	/** 
 	  *	 MUESTRA FORMULARIO NUEVO / EDICIÓN	
	  */
	public function muestraFormulario(){
		$Id_Usuario  = $this->input->post("Id_Usuario");
		$data = array(
			"roles"    => $this->Usuarios_model->CNS_Roles()
		);

		#	Si se recibe el Id_Usuario es un UPDATE
		if(is_numeric($Id_Usuario)){
			$data["usuario"] = $this->Usuarios_model->CNS_UsuarioByID($Id_Usuario);		
		}

		#	Carga vista
		$this->load->view("templates/formularios/form_usuario",$data);
	}

	/**
	 * INSERTA / ACUTALIZA CLIENTE
	 * SI RECIBE Id_Usuarios => Actualiza
	 * DE LO CONTRARIO INSERTA
	 */
	public function guardaUsuario(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");

		#	Id_Usuario para saber si es UPDATE
		$Id_Usuario = $this->input->post("Id_Usuario");

		#	Array de validaciones
		$rules = array(
			array("field" => "txtnombre", "label" => "Nombre", "rules" => "required|max_length[50]|html_escape|trim"),
			array("field" => "cmbrol", "label" => "Rol", "rules" => "required|integer|html_escape|trim"),
			array("field" => "txtusuario", "label" => "Usuario", "rules" => "required|max_length[50]|is_unique[Usuarios.Usuario.$Id_Usuario]|html_escape|trim"),
			array("field" => "rdostatus", "label" => "Status", "rules" => "required|html_escape|trim|integer"),
		);

		#	Array de validaciones del file
		$rules_file = array(
				"upload_path"   => "./usuariosprofile",
				"allowed_types" => "jpg|jpeg|png",
				"max_size"      => 2048,
				"encrypt_name"  => true
			);

		#	Si es un update no se valida que los campo sean requeridos
		if(is_numeric($Id_Usuario)){
			array_push($rules, array("field" => "pswcontrasena", "label" => "Contraseña", "rules" => "min_length[6]|max_length[18]|html_escape|trim"));
			array_push($rules, array("field" => "pswconf_contrasena", "label" => "Confirmar contraseña", "rules" => "matches[pswcontrasena]|html_escape|trim"));
		}else{
			array_push($rules, array("field" => "pswcontrasena", "label" => "Contraseña", "rules" => "required|min_length[6]|max_length[18]|html_escape|trim"));
			array_push($rules, array("field" => "pswconf_contrasena", "label" => "Confirmar contraseña", "rules" => "required|matches[pswcontrasena]|html_escape|trim"));
		}

		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);
		$this->load->library("upload",$rules_file);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}

		#	Contraseña sin encriptar
		$contrasena = $this->input->post("pswcontrasena");

		#	Datos a guardar
		$dataarray = array(
			"Id_Rol"          => $this->input->post("cmbrol"),
			"Nombre"          => $this->input->post("txtnombre"),
			"Usuario"         => $this->input->post("txtusuario"),
			"Status"          => intval($this->input->post("rdostatus")),
			"Fecha_Actualiza" => date("Y-m-d H:i:s"),
		);

		#	Si se recibe la contraseña se agrega al array
		if($contrasena){
			$dataarray["Contrasena"] = md5($contrasena);
		}

		#	Si se cargó una imagen se procesa
		if($_FILES["fleimagen"]["size"] > 0){
			#	Si se subió la imagen
			if($this->upload->do_upload("fleimagen")){
				#	Si se recibe el Id_Usuario (UPDATE), se borra la imagen anterior
				if(is_numeric($Id_Usuario)){
					$usuario = $this->Usuarios_model->CNS_UsuarioByID($Id_Usuario);
					$rutaborrar = "./usuariosprofile/".$usuario["Ruta_Imagen"];
					if(is_file($rutaborrar) && file_exists($rutaborrar)){
						unlink($rutaborrar);
					}
				}

				$dataarray["Ruta_Imagen"] = $this->upload->data("file_name");			
			}else{
				echo json_encode(array("head" => "_er:","body" => $this->upload->display_errors(" ","\n")));
				exit();
			}		
		}

		#	Si se recibe Id_Usuario: Acutaliza en base de datos
		if(is_numeric($Id_Usuario)){
			$ar = $this->Usuarios_model->UPD_Usuario($Id_Usuario,$dataarray);
			if($ar){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha agregado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}else{
		#	Si no se recibe Id_Usuario: Inserta en base de datos
			$dataarray["Fecha_Alta"] = date("Y-m-d H:i:s");
			$last = $this->Usuarios_model->INS_Usuario($dataarray);
			if($last){
				echo json_encode(array("head" => "_ok:","body" => "Excelete el registro se ha agregado exitosamente"));
			}else{
				echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
			}
		}
	}


	/**
	 * ELIMINA UN USUARIO
	 */
	public function eliminarUsuario(){
		#	Id_Usuario para eliminar
		$Id_Usuario = $this->input->post("Id_Usuario");

		#	Borra logo del usuario
		$usuario = $this->Usuarios_model->CNS_UsuarioByID($Id_Usuario);
		$rutaborrar = "./usuariosprofile/".$usuario["Ruta_Imagen"];
		if(is_file($rutaborrar) && file_exists($rutaborrar)){
			unlink($rutaborrar);
		}

		$ar  = $this->Usuarios_model->DEL_Usuario($Id_Usuario);
		if($ar){
			echo json_encode(array("head" => "_ok:","body" => "Excelete hemos eliminado tu registro"));
		}else{
			echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al eliminar tu registro, intentalo nuevamente"));
		}	
	}

}

/* End of file Usuarios.php */
/* Location: ./application/controllers/Usuarios.php */