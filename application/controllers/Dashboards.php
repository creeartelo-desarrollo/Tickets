<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Dashboards_model");	
	}

	/**
	 * CARGAS VISTAS DEL MÓDULO
	 */
	public function index(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$resources = array(
				"session" => $session,
				"modulojs" => "dashboards.js",
				"countticketsa" => $this->Dashboards_model->COUNT_Tickets_Abiertos(),							
			);

			$this->load->view("templates/header",$resources);
			$this->load->view("templates/sidebar",$resources);
			$this->load->view("dashboards",$resources);
			$this->load->view("templates/footer",$resources);
		}else{
			redirect(base_url());
		}
	}

	/**
	 * TRAE LOS PINES DE LOS TICKETS QUE 
	 * ABIERTOS
	 */
	public function traerPinesAbiertos(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_Tickets_Abiertos();
			echo json_encode($data);
		}
	}

	/**
	 * TRAE LOS PINES DE LOS TICKETS QUE 
	 * ESTÁN SIENDO ATENDIDOS
	 */
	public function traerPinesEnProceso(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_Tickets_En_Proceso();
			echo json_encode($data);
		}
	}

	/**
	 * TRAE LOS PINES DE LOS TICKETS QUE 
	 * AÚN NO ESTÁN SIENDO ATENDIDOS
	 */
	public function traerPinesSinAtender(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_Tickets_Sin_Atender();
			echo json_encode($data);
		}
	}


	/**
	 * TRAE LOS PINES DE LAS SUCURSALES ACTIVOS
	 */
	public function traerPinesSucursalesActivas(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_Sucursales(1);
			echo json_encode($data);
		}
	}

	/**
	 * TRAE LOS PINES DE LAS SUCURSALES INACTIVOS
	 */
	public function traerPinesSucursalesInactivas(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_Sucursales(0);
			echo json_encode($data);
		}
	}

	/**
	 * TRAE LOS PINES DE LOS USUARIOS ACTIVOS
	 */
	public function traerPinesUsuarios(){
		$session = $this->session->userdata();
		if(isset($session["Id_Usuario"])){
			$data = $this->Dashboards_model->CNS_UbicacionUsuarios();
			echo json_encode($data);
		}
	}

}

/* End of file Dashboards.php */
/* Location: ./application/controllers/Dashboards.php */