<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * CONSULTA UN USUARIO ACTIVO PARA EL LOGIN
	 * @param STRING $usuario    USUARIO
	 * @param STRING $contrasena CONTRASEÑA ENCRIPTADA
	 */
	function CALL_Login($usuario, $contrasena)
	{
		$query = $this->db->query("CALL SP_Login('$usuario','$contrasena')");
		$data  = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	  *	CONSULTA LOS TICKETS DEL USUARIO
	  *	@param INT $data_array IDENTIFICADOR DEL USUARIO
	  */
	function CALL_SPCNS_TicketsUsuario($Id_Usuario)
	{
		$data = $this->db->query("CALL SPNCNS_Tickets_Usuario($Id_Usuario)");
		return $data->result_array();
	}

	/**
	  *	INSERTA COORDENADAS DEL TICKET
	  *	@param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPINS_Ticket_Coordenadas($data_array)
	{	
		$str    = "CALL SPINS_Ticket_Coordenadas(?,?,?,?,?)";
		$query  = $this->db->query($str,$data_array);
		$data   = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	  *	INSERTA DIAGNOSTICO DEL TICKET
	  *	@param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPNINS_Ticket_Diagnostico($data_array)
	{
		$str    = "CALL SPNINS_Ticket_Diagnostico(?,?,?,?,?)";
		$query  = $this->db->query($str,$data_array);
		$data   = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	  *	INSERTA EVIDENCIA FOTOS
	  *	@param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPNINS_Evidencia_Fotos($data_array)
	{
		$str   = "CALL SPNINS_Evidencia_Fotos(?, ?, ?, ?, ?)";
		$query = $this->db->query($str, $data_array);
		$data  = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}	

	/**
	  *	CONSULTA TIEMPOS TICKET
	  *	@param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPCNS_Tiempos_Ticket($data_array)
	{
		$str   = "CALL SPCNS_Tiempos_Ticket(?,?)";
		$query = $this->db->query($str,$data_array);
		$data  = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $data;
	}	

	/**
	  *	INSERTA TIEMPO TICKET
	  * @param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPINS_Tiempo_Ticket($data_array)
	{
		$str   = "CALL SPINS_Tiempo_Ticket(?, ?, ?, ?)";
		$query = $this->db->query($str,$data_array);
		$data  = $query->result();

		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	  *	INSERTA TICKET FINALIZA
	  * @param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	  */
	function CALL_SPNINS_Ticket_Finaliza($data_array)
	{
		$str   = "CALL SPNINS_Ticket_Finaliza(?, ?, ?, ?, ?, ?)";
		$query = $this->db->query($str,$data_array);
		$data  = $query->result();

		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	 * ACTUALIZA EL STATUS DEL TICKET
	 * @param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	 */
	function CALL_SPUPD_Ticket_Status($data_array){
		$str    = "CALL SPUPD_Ticket_Status(?, ?, ?)";		
		$query  = $this->db->query($str,$data_array);
		$data   = $query->result();
		$query->next_result();
		$query->free_result();
		return $data;
	}
	
	/**
	 * ACTUALIZA UBICACIÓN USUARIO
	  * @param ID $Id_Usuario IDENTIFICADOR DEL USUARIO
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Ubicacion_Usuario($Id_Usuario,$dataarray){
		$this->db->where("Id_Usuario", $Id_Usuario);
		$this->db->update("Ubicacion_Usuarios", $dataarray);
		return $this->db->affected_rows();
	}	

	/**
	 * CONSULTA REGISTRO DE TICKETS POR SU ID
	 * @param INT $Id_Ticket IDENTIFICADOR DEL TICKET
	 */
	function CALL_SPCNS_TicketByID($Id_Ticket){
		$query = $this->db->query("CALL SPCNS_TicketByID($Id_Ticket)");
		$data  = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	 * ACTUALIZA EL TOKENFCM DEL USUARIO
	 * @param ARRAY $data_array ARRAY CON LOS PARAMETROS PARA EL SP
	 */
	function CALL_SPUPDA_Token_Usuario($data_array){
		$str   = "CALL SPUPD_Token_Usuario(?,?,?)";
		$query = $this->db->query($str,$data_array);
		$data  = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}

	/**
	 * ACTUALIZA UBICACIÓN USUARIO
	  * @param ID $Id_Usuario IDENTIFICADOR DEL USUARIO
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function CALL_SPUPD_Ubicacion_Usuario($data_array){
		$str   = "CALL SPUPD_Ubicacion_Usuario(?,?,?,?)";
		$query = $this->db->query($str,$data_array);
		$data  = $query->row();
		$query->next_result();
		$query->free_result();
		return $data;
	}	
}

/* End of file App_model.php */
/* Location: ./application/models/App_model.php */