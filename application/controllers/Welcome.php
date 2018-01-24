<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("App_model");
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	/**
	 * LOGIN DE LA APP
	 * @return [type] [description]
	 */
	public function login(){
		date_default_timezone_set("America/Mexico_City");
		$Fecha_Actualiza = date("Y-m-d H:i:s");
		$usuario         = $this->input->post("usuario");
		$contrasena      = $this->input->post("contrasena");
		$token           = $this->input->post("token");

		#	Consulta usuario y contraseña
		$result = $this->App_model->CALL_Login($usuario,$contrasena);
		
		$json = array(
			"res" => "ERR",
			"msg" => "Mmm, este usuario y/o contraseña no se encuentran el sistema, revisa la información"
		);

		if($result){
			#	Actualiza Token del FireBase
			$data_array = array($result->Id_Usuario,$token,$Fecha_Actualiza);
			$res = $this->App_model->CALL_SPUPDA_Token_Usuario($data_array);
	
			$json = array(
							"res" => "OK",
							"query" => $result
						);
		}

		echo json_encode($json);
	}

	/**
	 * CONSULTA LOS TICKETS DEL USUARIO
	 */
	public function traeTicketsUsuario(){
		$Id_Usuario	= $this->input->post("Id_Usuario");
		$result = $this->App_model->CALL_SPCNS_TicketsUsuario($Id_Usuario);
        
		$json = array(
							"res" => "OK",
							"query" => $result
						);
		
		echo json_encode($json);
	}

	/**
	 * GUARDA LAS COORDENADAS CUANDO LLEGA PASO 1, STATUS 3
	 * @return [type] [description]
	 */
	public function guardaLlegada(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");

		#	Recibe variables post
		$Id_Ticket       = $this->input->post("Id_Ticket");		
		$Fecha_Alta      = date("Y-m-d H:i:s");
		$Fecha_Actualiza = date("Y-m-d H:i:s");
		
		$Latitud         = $this->input->post("Latitud");
		$Longitud        = $this->input->post("Longitud");

		if(!$Latitud && !$Longitud){
			$Latitud  = 0;
			$Longitud = 0;
		}

		$data_array = array($Id_Ticket, $Latitud, $Longitud, $Fecha_Alta, $Fecha_Actualiza);
		$result     = $this->App_model->CALL_SPINS_Ticket_Coordenadas($data_array);

		$json = array(
			"res" => "ERR",
			"msg" => "Mmm, este usuario y/o contraseña no se encuentran el sistema, revisa la información"
		);

		if($result){
			#	Actualiza Status
			$this->actualizaStatus($Id_Ticket,3);

			$json = array(
							"res" => "OK",
							"query" => $result
						);
		}

		echo json_encode($json);
	}

	/**
	 * GUARDA EL DIAGNOSTICO PASO 2
	 */
	public function guardaDiagnostico(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");
		#	Id_Galeria para saber si es UPDATE
		$Id_Ticket = $this->input->post("Id_Ticket");

		#	Recibe variables post
		$Id_Ticket       = $this->input->post("Id_Ticket");
		$Diagnostico     = $this->input->post("diagnostico");
		$Material        = $this->input->post("material");
		$Fecha_Alta      = date("Y-m-d H:i:s");
		$Fecha_Actualiza = date("Y-m-d H:i:s");

		#	Guarda Diagnostico
		$data_array = array($Id_Ticket,$Diagnostico,$Material,$Fecha_Alta,$Fecha_Actualiza);
		$result = $this->App_model->CALL_SPNINS_Ticket_Diagnostico($data_array);

		if(!$result){		
			echo json_encode(array("res" => "ERR", "msg" => "Uh oh! Hubo un error al guardar, intentalo nuevamente"));
			exit();
		}

		#	Array de validaciones del file
		$rules_file = array(
				"upload_path"   => "./ticketsfiles",
				"allowed_types" => "jpg|jpeg|png",
				"max_size"      => 50120,
				"encrypt_name"  => true
			);

		#	Carga la libreria con las configuraciones
		$this->load->library("upload",$rules_file);

		#	Se suben las imagenes y se insertan en b.d.
		foreach ($_FILES as $filek => $filev) {
			if($this->upload->do_upload($filek)){
				$nombre_foto = $this->upload->data("file_name");

				$data_array  = array($Id_Ticket,1,$nombre_foto,$Fecha_Alta,$Fecha_Actualiza);
				$resfoto = $this->App_model->CALL_SPNINS_Evidencia_Fotos($data_array);
				if(!$resfoto){
					echo json_encode(array("res" => "ERR", "msg" => "Uh oh! Hubo un error al cargar alguna de las fotos, intentalo nuevamente"));
					exit();
				}
			}else{
				echo json_encode(array("res" => "ERR","msg" => $this->upload->display_errors(" ","\n")));
				exit();
			}		
		}

		#	Actualiza Status
		$this->actualizaStatus($Id_Ticket,4);

		#	Mensaje de exito si llegó hasta aquí
		echo json_encode(array("res" => "OK", "query" => $result));
	}	


	/**
	 * TRAE EN JSON LOS TIEMPOS
	 */
	public function traerTiempos(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");

		#	Variables del tiempo	
		$Id_Ticket = $this->input->post("Id_Ticket");
		$fecha = date("Y-m-d H:i:s");
		
		#	Consulta los registros de tiempo en un ticket
		$data_array = array($Id_Ticket,$fecha);
		$tiemposi = $this->App_model->CALL_SPCNS_Tiempos_Ticket($data_array);

		echo json_encode(array("res" => "OK", "query" => $tiemposi));
		
	}

	/**
	 * GUARDA TIEMPOS PASO 3 STATUS 4/5
	 * @return [type] [description]
	 */
	public function guardarTiempo(){
		#	Variables del post
		$terminado = $this->input->post("terminado");
		$Id_Ticket = $this->input->post("Id_Ticket");
		$Tipo      = $this->input->post("tipo");
		$Fecha     = date("Y-m-d H:i:s");
		$Razon     = $this->input->post("razon");
		
		#	Parametros del insert
		$data_array = array($Id_Ticket, $Tipo, $Fecha, $Razon);

		$result = $this->App_model->CALL_SPINS_Tiempo_Ticket($data_array);
		
		$json = array(
			"res" => "ERR",
			"msg" => "Error al guardar tiempo"
		);

		if($result){
			$json = array(
							"res" => "OK",
							"query" => $result
						);
			
			#	Actualiza Status
			if(isset($terminado)){
			 	$this->actualizaStatus($Id_Ticket,7);
			}else{
				if($Tipo == 1){
					$this->actualizaStatus($Id_Ticket,5);
					$Razon = "REANUDADO";
				}else{
					$this->actualizaStatus($Id_Ticket,6);
				}
			}
		}

		echo json_encode($json);
	}

	/**
	 * GUARDA LA EVIDENCIA FOTOGRAFICA
	 * PASO 4 
	 */
	public function guardaEvidencia(){
		date_default_timezone_set("America/Mexico_City");
		#	Id_Galeria para saber si es UPDATE
		$Id_Ticket = $this->input->post("Id_Ticket");

		#	Recibe variables post
		$Id_Ticket       = $this->input->post("Id_Ticket");
		$Fecha_Alta      = date("Y-m-d H:i:s");
		$Fecha_Actualiza = date("Y-m-d H:i:s");

		#	Array de validaciones del file
		$rules_file = array(
				"upload_path"   => "./ticketsfiles",
				"allowed_types" => "jpg|jpeg|png",
				"max_size"      => 50120,
				"encrypt_name"  => true
			);

		#	Carga la libreria con las configuraciones
		$this->load->library("upload",$rules_file);

		#	Se suben las imagenes y se insertan en b.d.
		foreach ($_FILES as $filek => $filev) {
			if($this->upload->do_upload($filek)){
				$nombre_foto = $this->upload->data("file_name");

				$data_array = array($Id_Ticket,2,$nombre_foto,$Fecha_Alta,$Fecha_Actualiza);
				$resfoto = $this->App_model->CALL_SPNINS_Evidencia_Fotos($data_array);
				if(!$resfoto){
					echo json_encode(array("res" => "ERR", "msg" => "Uh oh! Hubo un error al cargar alguna de las fotos, intentalo nuevamente"));
					exit();
				}
			}else{
				echo json_encode(array("res" => "ERR","body" => $this->upload->display_errors(" ","\n")));
				exit();
			}		
		}

		#	Actualiza Status
		$this->actualizaStatus($Id_Ticket,8);
		
		#	Mensaje de exito si llegó hasta aquí
		echo json_encode(array("res" => "OK", "query" => "Evidencia guardada"));
	}

	/**
	 * GUARDA LA FOTO DE DOCUMENTO FINAL
	 * PASO 5 STATUS 6
	 */
	public function guardaFinal(){
		date_default_timezone_set("America/Mexico_City");

		#	Recibe variables post
		$Id_Ticket       = $this->input->post("Id_Ticket");
		$Orden_Servicio  = $this->input->post("ordenserv");
		$Costo  		 = floatval($this->input->post("costo"));
		$Observaciones   = $this->input->post("observaciones");
		$Fecha_Alta      = date("Y-m-d H:i:s");
		$Fecha_Actualiza = date("Y-m-d H:i:s");
		
		// $Id_Ticket = 1;
		// $Orden_Servicio = 1321;
		// $Observaciones = "dasds";

		#	Array de validaciones del file
		$rules_file = array(
				"upload_path"   => "./ticketsfiles",
				"allowed_types" => "jpg|jpeg|png",
				"max_size"      => 50120,
				"encrypt_name"  => true
			);

		#	Carga la libreria con las configuraciones
		$this->load->library("upload",$rules_file);

		#	Se sube las imagen y se inserta en b.d.
		if($this->upload->do_upload("foto1")){
			$nombre_foto = $this->upload->data("file_name");
        	
        	#	Inserta en tabla de imagenes
        	$data_arrayf = array($Id_Ticket,3,$nombre_foto,$Fecha_Alta,$Fecha_Actualiza);
			$resfoto     = $this->App_model->CALL_SPNINS_Evidencia_Fotos($data_arrayf);
			if(!$resfoto){
				echo json_encode(array("res" => "ERR", "msg" => "Uh oh! Hubo un error al cargar alguna de las fotos, intentalo nuevamente"));
				exit();
			}

			#	Inserta en tabla de finaliza
			$data_arrayd = array($Id_Ticket, $Orden_Servicio, $Costo, $Observaciones, $Fecha_Alta,$Fecha_Actualiza);
			$resdata     = $this->App_model->CALL_SPNINS_Ticket_Finaliza($data_arrayd);
			if(!$resdata){
				echo json_encode(array("res" => "ERR", "msg" => "Uh oh! Hubo un error al guardar la información"));
				exit();
			}
		}else{
			echo json_encode(array("res" => "ERR","body" => $this->upload->display_errors(" ","\n")));
			exit();
		}		
		

		#	Actualiza Status
		$this->actualizaStatus($Id_Ticket,9);

		#	Mensaje de exito si llegó hasta aquí
		echo json_encode(array("res" => "OK", "query" => "Evidencia guardada"));
	}

	public function consultaTicket(){
		#	Id_Galeria para saber si es UPDATE
		$Id_Ticket = $this->input->post("Id_Ticket");

		$ticket = $this->App_model->CALL_SPCNS_TicketByID($Id_Ticket);

		$json = array(
			"res" => "ERR",
			"msg" => "Mmm, este ticket no se encuentra mas en el sistema"
		);

		if($ticket){
			$json = array(
							"res" => "OK",
							"query" => $ticket
						);
		}

		echo json_encode($json);
		
	}

	/**
	 * ACTUALIZA COORDENADAS DEL USUARIO DE LA APP MOVIL
	 */
	public function actualizaUbicacionUsuario(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");

		#	Recibe variables post
		$Id_Usuario      = $this->input->post("Id_Usuario");		
		$Fecha_Actualiza = date("Y-m-d H:i:s");
		
		$Latitud         = $this->input->post("Latitud");
		$Longitud        = $this->input->post("Longitud");

		// $Id_Usuario = 5;
		// $Latitud = "54456";
		// $Longitud = "dasd";

		if(!$Latitud && !$Longitud){
			$Latitud  = 0;
			$Longitud = 0;
		}

		$data_array = array($Id_Usuario, $Latitud, $Longitud, $Fecha_Actualiza);
		$result     = $this->App_model->CALL_SPUPD_Ubicacion_Usuario($data_array);

		$json = array(
			"res" => "ERR",
			"msg" => "Error al enviar tu ubicación"
		);

		if($result){
			$json = array(
							"res" => "OK",
							"query" => $result
						);
		}

		echo json_encode($json);
	}
	

	/**
	 * ACTUALIZA STATUS DEL TICKET
	 * @param  INT $Id_Ticket IDENTIFICADOR DEL TICKET
	 * @param  INT $Status    STATUS NUEVO
	 */
	private function actualizaStatus($Id_Ticket, $Status){
		date_default_timezone_set("America/Mexico_City");
		$Fecha_Actualiza = date("Y-m-d H:i:s");

		$data_array = array($Id_Ticket,$Status,$Fecha_Actualiza);
		$res = $this->App_model->CALL_SPUPD_Ticket_Status($data_array);
		return $res;
	}

	public function actualizaTokenFCM(){
		date_default_timezone_set("America/Mexico_City");
		
		#	Variables
		$Fecha_Actualiza = date("Y-m-d H:i:s");
		$Id_Usuario      = $this->input->post("Id_Usuario");
		$TokenFCM        = $this->input->post("token");
		
		$data_array = array($Id_Usuario,$TokenFCM,$Fecha_Actualiza);
		$res = $this->App_model->CALL_SPUPDA_Token_Usuario($data_array);

		$json = array(
			"res" => "ERR",
			"msg" => "Hubo un error al actualizar el Token FCM"
		);

		if($res){
			$json = array(
							"res" => "OK",
							"msg" => "Token actualizado"
						);
		}

		echo json_encode($json);

	}

	public function test(){
		//$res = $this->actualizaTokenFCM(1,"eYrBJd6CEjY:APA91bGUe-diSAVHTYwzDFQJO_fUruMlz-BzNY5kTcinvjQOYo0KsvKVn0YXgCeO-YePtReev8cInNveiFyxGwi0KKKFX4EqJdpjHhQDu1T-C6qY3wBHZyaJw9YruBsHaMTm-tW4w5VO");
		
		$this->guardaFinal();
	}
}
// SPNINS_Ticket_Diagnostico