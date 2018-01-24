<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Tickets_model");	
	}

	/**
	 * CARGAS VISTAS DEL MÓDULO TICKETS ABIERTOS
	 */
	public function index(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$resources = array(
				"session"       => $session,
				"modulojs"      => "tickets.js",	
			);

			$this->load->view("templates/header",$resources);
			$this->load->view("templates/sidebar",$resources);
			$this->load->view("tickets",$resources);
			$this->load->view("templates/footer",$resources);
		}else{
			redirect(base_url());
		}
	}

	/**
	 * CARGAS VISTAS DEL MÓDULO TICKETS CERRADOS
	 */
	public function cerrados(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$resources = array(
				"session"       => $session,
				"primer_fecha"  => $this->Tickets_model->CNS_LastTicket("ASC")->Fecha,
				"modulojs"      => "tickets_cerrados.js",	
			);

			$this->load->view("templates/header",$resources);
			$this->load->view("templates/sidebar",$resources);
			$this->load->view("tickets_cerrados",$resources);
			$this->load->view("templates/footer",$resources);
		}else{
			redirect(base_url());
		}
	}

	/**
	  * LISTADO DE TICKETS
	  */
	public function listarTicketsAbiertos()
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
        $total =  $this->Tickets_model->COUNT_Tickets("A");

        if($busqueda){
            $tickets = $this->Tickets_model->CNS_TicketsAbiertosMatch($skip, $take, $order_field, $order, $busqueda, "A");
            $filtered_rows = sizeof($tickets);
        }else{
            $tickets = $this->Tickets_model->CNS_TicketsAbiertos($skip, $take, $order_field, $order, "A");
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
	  * LISTADO DE TICKETS
	  */
	public function listarTicketsCerrados()
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
        $total =  $this->Tickets_model->COUNT_Tickets("C");

        if($busqueda){
            $tickets = $this->Tickets_model->CNS_TicketsCerradosMatch($skip, $take, $order_field, $order, $busqueda, "C");
            $filtered_rows = sizeof($tickets);
        }else{
            $tickets = $this->Tickets_model->CNS_TicketsCerrados($skip, $take, $order_field, $order,"C");
            $filtered_rows = $total;
        }

        $data = array(
            "draw" => $draw,
            "recordsTotal" =>  $total,
            "recordsFiltered" =>  $filtered_rows,
            "data" => $tickets   
        );    

        // $this->output->enable_profiler(TRUE);    
        echo json_encode($data);
	}

	/**
	 * MUESTRA JSON DE LAS DIRECCIONES DE UN CLIENTE
	 * @return [type] [description]
	 */
	public function muestraDireccionesCliente(){
		#	Id_Cliente para consultar	
		$Id_Cliente = $this->input->post("Id_Cliente");
		
		#	Consulta
		$direcciones = $this->Tickets_model->CNS_DireccionesCliente($Id_Cliente);
		echo json_encode($direcciones);
	}

	/**
	 * MUESTRA JSON DE UNA DIRECCION
	 */
	public function muestraDireccion(){
		#	Id_Direccion para consultar	
		$Id_Direccion = $this->input->post("Id_Direccion");
		
		#	consulta		
		$direccion = $this->Tickets_model->CNS_DireccionByID($Id_Direccion);
		echo json_encode($direccion);
	}

	/** 
 	  *	 MUESTRA FORMULARIO NUEVO / EDICIÓN	
	  */
	public function muestraFormulario(){
		$Id_Ticket  = $this->input->post("Id_Ticket");
		$clientes   =  $this->Tickets_model->CNS_Clientes();
		$Id_Cliente = $clientes ? $clientes[0]["Id_Cliente"] : 0;

		$data = array(
			"usuarios"    => $this->Tickets_model->CNS_Cuadrillas(),
			"clientes"    => $clientes,
			"direcciones" => $this->Tickets_model->CNS_DireccionesCliente($Id_Cliente)
		);

		#	Si se recibe el Id_Ticket es un UPDATE
		if(is_numeric($Id_Ticket)){
			$data["ticket"]      = $this->Tickets_model->CNS_TicketByID($Id_Ticket);	
			$data["direcciones"] = $this->Tickets_model->CNS_DireccionesCliente($data["ticket"]["Id_Cliente"]);	
			$data["diagnostico"] = $this->Tickets_model->CNS_TicketDiagnostico($Id_Ticket);	
			$data["finaliza"]    = $this->Tickets_model->CNS_TicketFinaliza($Id_Ticket);	
		}else{
			$lastnumero = $this->Tickets_model->CNS_LastTicket("DESC");
			$consecutivo = $lastnumero ? substr($lastnumero->Num_Seguimiento,3): 0;
			// $consecutivo = ;
			$consecutivo = $consecutivo + 1;
			$data["nuevo_numero"] = "MK-" . str_pad($consecutivo, 4, "0", STR_PAD_LEFT);
		}
		#	Carga vista
		$this->load->view("templates/formularios/form_ticket",$data);
		//$this->output->enable_profiler(TRUE);
	}

	/**
	 * INSERTA / ACUTALIZA TICKET
	 * SI RECIBE Id_Ticket => Actualiza
	 * DE LO CONTRARIO INSERTA
	 */
	public function guardaTicket(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");
		#	Id_Ticket para saber si es UPDATE
		$Id_Ticket  = $this->input->post("Id_Ticket");
		$Id_Usuario = $this->input->post("cmbasignado");
		$notifica   = $this->input->post("rdonotofica");

		#	Array de validaciones
		$rules = array(
			array("field" => "txtfecha", "label" => "Fecha", "rules" => "required|valid_date|html_escape|trim"),
			array("field" => "txtnoseguimiento", "label" => "Número de Seguimiento", "rules" => "required|max_length[20]|is_unique[Tickets.Num_Seguimiento.$Id_Ticket]|html_escape|trim"),
			array("field" => "txtticket", "label" => "Ticket", "rules" => "max_length[100]|html_escape|trim"),
			array("field" => "cmbcliente", "label" => "Empresa", "rules" => "required|integer|html_escape|trim"),
			array("field" => "cmbdireccion", "label" => "Dirección", "rules" => "required|integer|html_escape|trim"),
			array("field" => "txadescripcion", "label" => "Número de Seguimiento", "rules" => "required|max_length[200]|html_escape|trim"),
			array("field" => "txaobservaciones", "label" => "Número de Seguimiento", "rules" => "max_length[500]|html_escape|trim"),
			array("field" => "cmbasignado", "label" => "Número de Seguimiento", "rules" => "required|integer|html_escape|trim"),
		);

		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}


		#	Array para la query
		$data = array(
				"Id_Usuario"      => $Id_Usuario,
				"Fecha"           => $this->input->post("txtfecha"),				
				"Num_Seguimiento" => $this->input->post("txtnoseguimiento"),
				"Ticket"          => $this->input->post("txtticket"),
				"Id_Cliente"      => $this->input->post("cmbcliente"),
				"Id_Direccion"    => $this->input->post("cmbdireccion"),
				"Descripcion"     => $this->input->post("txadescripcion"),
				"Observaciones"   => $this->input->post("txaobservaciones"),	
				"Fecha_Actualiza" => date("Y-m-d H:i:s"),
			 );

		
		#	Si ticket está asignado el status es
		#	1 : Levantado
		#	De lo contrario es:
		#	2 : Asignado
        
		if($data["Id_Usuario"] > 0){
			$data["Status"] = 2;
		}else{
			$data["Status"] = 1;
		} 


		#	Si el ticket ya se está atendiendo 
		#	se quitan los campos que no se pueden editar
		if(is_numeric($Id_Ticket)){
			$ticket = $this->Tickets_model->CNS_TicketByID($Id_Ticket);
			if($ticket["Status"] > 2){
				unset($data["Status"]);
				unset($data["Id_Usuario"]);
				unset($data["Id_Cliente"]);
				unset($data["Id_Direccion"]);
			}
		}		
		

		if(is_numeric($Id_Ticket)){
			$ar = $this->Tickets_model->UPD_Ticket($Id_Ticket,$data);
			if($ar > 0){
				$res = array("head" => "_ok:","body" => "Excelete hemos actualizado el registro");
				$datanoty = array(
					"titulo" => "Se ha modificado un ticket",
					"mensaje" => "Se ha modifcado el ticket " . $data["Num_Seguimiento"] .", revisalo en tu lista"
				);

				if($notifica == 1 && $Id_Usuario > 0){
					$oknoti = $this->sendNotificacion($Id_Usuario,$datanoty);
					if(!$oknoti){
						$res = array("head" => "_er:","body" => "Ooops! Hubo un error al notifcar al usuario, itenta nuevamente");
					}
				}

			}else{
				$res = array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente");
			}	

			
		}else{
			#	Se envía Fecha_Alta y status si es insert
			$data["Fecha_Alta"] = date("Y-m-d H:i:s");
			
			#	Se inserta a la B.D.
			$last = $this->Tickets_model->INS_Ticket($data);
			if($last){
				$res = array("head" => "_ok:","body" => "Excelete el registro se ha agregado exitosamente");

				$datanoty = array(
					"titulo" => "Nuevo Ticket",
					"mensaje" => "Se ha levantado un nuevo ticket revísalo en tu lista"
				);
				
				if($notifica == 1 && $data["Id_Usuario"] > 0){
					$oknoti = $this->sendNotificacion($data["Id_Usuario"],$datanoty);
					if(!$oknoti){
						$res = array("head" => "_er:","body" => "Ooops! Hubo un error al notifcar al usuario, itenta nuevamente");
					}
				}

			}else{
				$res = array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente");
			}
		}
		echo json_encode($res);
	}


	/**
	 * ACUTALIZA LOS DATOS PROPORCIONADOS POR LA CUADRILLA
	 * @return [type] [description]
	 */
	public function guardaTicketCuadrilla(){
		#	Establece zona Horaria
		date_default_timezone_set("America/Mexico_City");

		$Id_Ticket = $this->input->post("Id_Ticket");

		#	Array de validaciones
		$rules = array(
			array("field" => "txadiagnostico", "label" => "Diagnóstico", "rules" => "required|max_length[800]|html_escape|trim"),
			array("field" => "txamaterial", "label" => "Material", "rules" => "max_length[1000]|html_escape|trim"),
			array("field" => "txaobservaciones", "label" => "Observaciones", "rules" => "max_length[500]|html_escape|trim"),
		);

		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}

		$dataarray_d = array(
			"Diagnostico"     => $this->input->post("txadiagnostico"),
			"Material"        => $this->input->post("txamaterial"),
			"Fecha_Actualiza" => date("Y-m-d H:i:s"),
		);
		
		$dataarray_f = array(
			"Observaciones"   => $this->input->post("txaobservaciones"),
			"Fecha_Actualiza" => date("Y-m-d H:i:s"),
		);
		
		$ar_d = $this->Tickets_model->UPD_Ticket_Diagnostico($Id_Ticket,$dataarray_d);
		$ar_f = $this->Tickets_model->UPD_Ticket_Finaliza($Id_Ticket,$dataarray_f);

		if($ar_d > 0 && $ar_f){
			echo json_encode(array("head" => "_ok:","body" => "Excelete hemos actualizado el registro"));			
		}else{
			echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al agregar tu registro, intentalo nuevamente"));
		}

	}

	/**
	 * ENIVAR NOTIFICACIÓN AL USUARIO
	 * @param  INT $Id_Usuario IDENTIFICADOR DEL USUARIO 
	 *                         AL QUE SE LE ENVIARÁ LA NOTIFICACIÓN
	 * @param  ARRAY $dataarray  ARRAY ASOCIATIVO CON LAS VARIABLES 
	 *                           PARA ARMAR LA NOTIFICACIÓN
	 */
	private function sendNotificacion($Id_Usuario, $dataarray){
		#	Variables de la notificación
		$titulo = $dataarray["titulo"];
		$mensaje = $dataarray["mensaje"];

		#	Varibles de solicitud para la curl
		$path_to_fcm = "https://fcm.googleapis.com/fcm/send";
		$server_key = "AAAA6UX6Wqw:APA91bEO8bAPe1hTbT861vyYDSfoGASCtOUwH6x2uSsdxSyCR7EZ3L9lxjUSRxfTbQKxTJ_eW5vdp5z7kWrQgJuZc7nrVRScu_H5ltNNQDTHS6fnZuI-TdJ5e0_PpP3eCgamfg1ceEaw";

		#	Se obtiene el token del usuario al que se le enviará la notificación
		$usuario = $this->Tickets_model->CNS_UsuarioByID($Id_Usuario);
		$TokenUsuario = $usuario["TokenFCM"];

		if(!$TokenUsuario){
			return false;
			exit();
		}

		#	Encabezados del request
		$headers = array(
			"Authorization:key=" . $server_key,
			"Content-Type:application/json",
		);

		#	Cuerpo del request
		$fields = array(
			"to" => $TokenUsuario,
			"notification" => array(
				"title" => $titulo,
				"body" => $mensaje,
				"sound" => "default"
			),
			"data" => array(
				"message" => "message body",
		    	"click_action" => "PUSH_INTENT"
			),
			"priority" => "normal"
		);


		$payload = json_encode($fields);

		#	Se hace la solicitud CURL
		$ch = curl_init($path_to_fcm);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		$result = curl_exec($ch);
		curl_close($ch);

		#	Se codifica el JSON para obtener ol número de exitos
		$jsond = json_decode($result);
		if($jsond->success > 0){
			return true;
		}
		
	}

	public function pdf($Id_Ticket){
		// $session = $this->session->userdata();
		// if(isset($session["Id_Usuario"])){
			$nombre_archivo = "Reporte.pdf";
				$data = array(
				"ticket"             => $this->Tickets_model->CNS_TicketByID($Id_Ticket),
				"ticket_diagnostico" => $this->Tickets_model->CNS_TicketDiagnostico($Id_Ticket),
				"ticket_imagenesi"   => $this->Tickets_model->CNS_TicketImagenes($Id_Ticket,1),
				"ticket_imagenesf"   => $this->Tickets_model->CNS_TicketImagenes($Id_Ticket,2),
				"ticket_finaliza"	 => $this->Tickets_model->CNS_TicketFinaliza($Id_Ticket),
				"reporte"  		 	 => $this->Tickets_model->CNS_TicketImagenes($Id_Ticket,3),
			);

			$html = $this->load->view("templates/docs/pdf_ticket", $data, TRUE);
			
			$this->load->library("Pdf");
			$pdf = new Pdf("P", "mm", "Letter", true, "UTF-8", false);			
			$pdf->setTitle("Reporte");
			$pdf->SetPrintFooter(FALSE);
			$pdf->SetMargins(15, 50, 15);
			$pdf->SetHeaderData("assets/libs/admin/images/logo.png",80,"DIRECCIÓN","Avenida de la Cantera 39-A \nJardines de Sta. Maria  \nTlaquepaque Jalisco CP: 45530  \nTel: 33-36 78-20");
			$pdf->SetHeaderMargin(15);	        
			$pdf->setPrintFooter(false);
			$pdf->AddPage();
			$pdf->writeHTML($html);
			$pdf->Output($nombre_archivo, "I");
	//	}
	}

	/**
	 * ELIMINA TICKET Y LOS REGISTROS RELACIONADAS EN LAS OTRAS TABLAS 
	 * Evidencia_Fotos
	 * Tiempos_Ticket
	 * Ticket_Diagnostico
	 * Ticket_Coordenadas
	 * Ticket_Finaliza
	 * Tickets 
	 */
	public function eliminarTicket(){
		#	Id_Ticket para eliminar	
		$Id_Ticket = $this->input->post("Id_Ticket");

		#	Consulta las imagenes de evidencia 
		#	para eliminar los archivos
		$evidencia = $this->Tickets_model->CNS_TicketImagenes($Id_Ticket);
		foreach ($evidencia as $ekey => $eval) {
			$rutaborrar = "./ticketsfiles/".$eval["Ruta_Imagen"];
			if(is_file($rutaborrar) && file_exists($rutaborrar)){
				unlink($rutaborrar);
			}
		}

		#	Elimina evidencia fotos
		$are = $this->Tickets_model->DEL_EvidenciaFotos($Id_Ticket);

		#	Elimina tiempos
		$art = $this->Tickets_model->DEL_TiemposTicket($Id_Ticket);

		#	Elimina evidencia fotos
		$ard = $this->Tickets_model->DEL_DiagnosticoTicket($Id_Ticket);

		#	Elimina coordenadas
		$arc = $this->Tickets_model->DEL_CoordenadasTicket($Id_Ticket);

		#	Elimina evidencia fotos
		$arf = $this->Tickets_model->DEL_FinalizaTicket($Id_Ticket);

		#	Elimina ticket
		$ar = $this->Tickets_model->DEL_Ticket($Id_Ticket);

		if($ar){
			echo json_encode(array("head" => "_ok:","body" => "Excelete hemos eliminado tu registro"));
		}else{
			echo json_encode(array("head" => "_er:","body" => "Ooops! Hubo un error al eliminar tu registro, intentalo nuevamente"));
		}
	}

	/**
	 * EXPORTA TICKETS A ARCHIVO DE EXCEL
	 * CON LO PARAMETROS RECIBIDOS DEL FORMULARIO
	 * */
	public function validaExcel(){
		#	Array de validaciones
		$rules = array(
			array("field" => "txtfechai", "label" => "Fecha inicial", "rules" => "required|valid_date|html_escape|trim"),
			array("field" => "txtfechaf", "label" => "Fecha final", "rules" => "required|valid_date|is_date_greather_than[txtfechai]|html_escape|trim"),
			array("field" => "chkfiltros[]", "label" => "Filtros", "rules" => "required|html_escape|trim"),
		);

		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);

		#	Si cumple las validaciones		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array("head" => "_er:","body" => validation_errors(" ","\n")));
			exit();
		}

		$Fecha_Inicio = $this->input->post("txtfechai");
		$Fecha_Fin    = $this->input->post("txtfechaf");
		$Filtros 	  = $this->input->post("chkfiltros");
		$Strfiltros	  = implode("-", $Filtros);
		// $Fecha_Inicio = "2017-11-10";
		// $Fecha_Fin	  = "2018-01-10";
		// $Filtros = array("a","c");

		$result = array(
			"head"	=> "_ok:",
			"body"	=> base_url("tickets/reporte/"	.	$Fecha_Inicio	.	"/"	.	$Fecha_Fin	.	"/"	.	$Strfiltros)
		);

		echo json_encode($result);

	}

	public function reporte($Fecha_Inicio,$Fecha_Fin,$Strfiltros){
		$Filtros = explode("-", $Strfiltros);
		# Consulta tickets con los filtros recibidos
		$tickets = $this->Tickets_model->CNS_TicketsFiltros($Fecha_Inicio,$Fecha_Fin,$Filtros);

		#	Carga libreria de excel
		$this->load->library("PHPExcel");
		$excel = new PHPExcel();

		#	Se configuran las propiedades del archivo
		$excel->getProperties()->setCreator("Mako");
		$excel->getProperties()->setLastModifiedBy("Mako");
		$excel->getProperties()->setTitle("Tickets Mako");
		$excel->getProperties()->setSubject("Tickets Mako");
		$excel->getProperties()->setDescription("Tickets Mako");
		$hoja = $excel->getActiveSheet();

		#	Array con el formato del encabezado
		$hoja->getStyle("A1:K1")->applyFromArray(array(
				  	"fill" => array(
				  	"type" => PHPExcel_Style_Fill::FILL_SOLID,
				  	"color" => array("rgb" => "CB6120")
				),
					"font" => array(
					"bold" => true,
					"color" => array("rgb" => "FFFFFF"),
					"size"	=> 13
				)
		    ));

		#	Escribe encabezados
		$hoja->SetCellValue("A1", "Fecha");
		$hoja->SetCellValue("B1", "No. Seguimiento");
		$hoja->SetCellValue("C1", "Ticket");
		$hoja->SetCellValue("D1", "Empresa");
		$hoja->SetCellValue("E1", "Sucursal");
		$hoja->SetCellValue("F1", "Dirección");
		$hoja->SetCellValue("G1", "Usuario");
		$hoja->SetCellValue("H1", "Alerta");
		$hoja->SetCellValue("I1", "Tiempo");
		$hoja->SetCellValue("J1", "Orden Servicio");
		$hoja->SetCellValue("K1", "Estatus");


		#	Se recorren los registros para escribirlos en el excel
		foreach ($tickets as $index => $val) {
			$row       = $index + 2;
			$strstatus = $this->cualStatus($val["Status"]);
			$color     = str_replace("#", "", $val["Alerta"]);
			
			$hoja->SetCellValue("A".$row, $val["Fecha_Alta"]);
			$hoja->SetCellValue("B".$row, $val["Num_Seguimiento"]);
			$hoja->SetCellValue("C".$row, $val["Ticket"]);
			$hoja->SetCellValue("D".$row, $val["Razon_Social"]);
			$hoja->SetCellValue("E".$row, $val["Sucursal"]);
			$hoja->SetCellValue("F".$row, $val["Direccion"]);
			$hoja->SetCellValue("G".$row, $val["Usuario"]);
			$hoja->getStyle("H".$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$hoja->getStyle("H".$row)->getFill()->getStartColor()->setRGB($color);
			$hoja->SetCellValue("I".$row, $val["Intervalo"]);
			$hoja->SetCellValue("J".$row, $val["Orden_Servicio"]);
			$hoja->SetCellValue("K".$row, $strstatus);
		}

		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="tickets.xlsx"');
        header('Cache-Control: max-age=0'); 
		
		
		$objWriter = new PHPExcel_Writer_Excel2007($excel);
		$objWriter->save("php://output");
	}

	/**
	 * REGRESA EL NOMBRE DEL ESTADO DEL TICKET 
	 * DE ACUERDO AL ENTERO DEL PARAMETRO
	 * 
	 * @param  {[INT]} intsatus [STATUS]
	 * @return {[STRING]} strstatus [NOMBRE DEL STATUS]
	 */
	private function cualStatus($intsatus){
		$strstatus = "";
		switch($intsatus){
                case 1:
                    $strstatus = "LEVANTADO";
                    break;
                case 2:
                    $strstatus = "ASIGNADO";
                    break;
                case 3:
                    $strstatus = "EN EL LUGAR";
                    break;
                case 4:
                    $strstatus = "DIAGNOSTICANDO";
                    break;
                case 5:
                    $strstatus = "TRABAJANDO";
                    break;
                case 6:
                    $strstatus = "PAUSADO";
                    break;
                case 7:
                    $strstatus = "EVIDENCIANDO";
                    break;
                case 8:
                    $strstatus = "TERMINADO";
                    break;
                case 9:
                    $strstatus = "CERRADO";
                    break;
                default:
                    $strstatus = "INDEFINIDO";
                    break;
            }
            return $strstatus;
	}

	public function test(){
		$Id_Ticket = 1;
		$tiempos = $this->Tickets_model->CNS_TiemposTicket($Id_Ticket);
		$intervalos = array_chunk($tiempos, 2);

		$ultimo = end($intervalos);
		if(sizeof($ultimo) == 1){
			$intervalos[sizeof($intervalos) -2][1]["Fecha"] = $ultimo[0]["Fecha"];
			array_pop($intervalos);
		}

		$tiempo = 0;
		foreach ($intervalos as $ikey => $ival) {			
			$fechai = new DateTime($ival[0]["Fecha"]);
			$fechaf = new DateTime($ival[1]["Fecha"]);

			$intervalo = $fechai->diff($fechaf);

			echo $intervalo->format("%y") . " - ";
			echo $intervalo->format("%m") . " - ";
			echo $intervalo->format("%a") . " - ";
			echo $intervalo->format("%h") . " - ";
			echo $intervalo->format("%i") . "<br>";
		}

		echo "<pre>";
		print_r($intervalos);
		echo "</pre>";
	}


	function test2(){
		$datanoty = array(
					"titulo" => "Se ha modificado un ticket",
					"mensaje" => "Se ha modifcado el ticket "
				);
		$this->sendNotificacion(3, $datanoty);
	}
}
