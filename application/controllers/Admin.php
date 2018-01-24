<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("App_model");	
	}

	/**
	  * ABRE VISTA DE LOGIN
	  */
	public function index()
	{
		$session = $this->session->userdata();
		if(!isset($session["Id_Usuario"])){
			$this->load->view("login");
		}else{
			redirect(base_url("dashboards"));
		}
	}

	/**
	 * LOGIN A LA APP
	 */
	public function logIn()
	{		
		$rules = array(
			array("field" => "txtusuario", "label" => "Usuario", "rules" => "trim|required|xss_clean"),
			array("field" => "pswcontrasena", "label" => "Contraseña", "rules" => "trim|required|xss_clean"),
		);
		
		#	Carga las librerias
		$this->load->library("form_validation");
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == true)
        {
	        $usuario = $this->input->post("txtusuario");
	        $contrasena = $this->input->post("pswcontrasena");
	        $contrasena = md5($contrasena);
	        $login = $this->App_model->CALL_Login($usuario, $contrasena);

	        if($login){
	            #	Se mandan los datos del login a session
	        	$sessionarray = array(
						"Id_Usuario"         => $login->Id_Usuario,
						"Rol"         		 => $login->Rol,
						"Nombre"         	 => $login->Nombre,
						"Ruta_Imagen"        => $login->Ruta_Imagen,
	        		);
	        	$this->session->set_userdata($sessionarray);
	        	$this->session->set_flashdata("msg","Hola " . $login->Nombre . " Bienvenid@");
	        	$res = array("head" => "_ok:","body" => base_url("dashboards"));	        	
	        }else{
	        	$res = array("head" => "_er:","body" => "Oopps! Tu usuario y/o contraseña no se encuentran");
	        }
	    }else{
	    	$res = array("head" => "_er:","body" => validation_errors(" ","\n"));
	    }

	    echo json_encode($res);
	}

	/**
	  * LOGOUT
	  */
	public function logOut()
	{
		$sessionarray = array("Id_Usuario","Rol","Nombre","Ruta_Imagen");
		$this->session->unset_userdata($sessionarray);
		redirect(base_url());
	}
}
