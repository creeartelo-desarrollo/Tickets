<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	  *	CUENTA EL TOTAL DE USUARIOS EN LA TABLA
	  */
	function COUNT_Usuarios(){
		$this->db->select("Id_Usuario");
		$this->db->from("Usuarios");
		return $this->db->count_all_results();
	}

	/**
	 * CONSULTA USUARIOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_Usuarios($Skip, $Take, $Order_Field, $Order){
		$this->db->select("u.Id_Usuario, u.Usuario, u.Nombre, u.Ruta_Imagen, 
						   u.Status, r.Nombre as Rol");
		$this->db->from("Usuarios as u");
		$this->db->join("Roles as r", "u.Id_Rol = r.Id_Rol", "inner");
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA USUARIOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_UsuariosMatch($Skip, $Take, $Order_Field, $Order, $Match){
		$this->db->select("u.Id_Usuario, u.Usuario, u.Nombre, u.Ruta_Imagen, 
						   u.Status, r.Nombre as Rol");
		$this->db->from("Usuarios as u");		
		$this->db->join("Roles as r", "u.Id_Rol = r.Id_Rol", "inner");
		$this->db->like("CONCAT(u.Usuario, ' ', u.Nombre, ' ', r.Nombre)", $Match);
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA CATÁLOGO DE ROLES
	 */
	function CNS_Roles(){
		$this->db->select("Id_Rol, Nombre");
		$this->db->from("Roles");
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA UN USUARIO POR SU ID 
	 * @param INT $Id_Usuario IDENTIFICADOR DEL USUARIO
	 */
	function CNS_UsuarioByID($Id_Usuario){
		$this->db->select("u.Id_Usuario, u.Nombre, u.Usuario, u.Ruta_Imagen, 
						   u.Status, u.Id_Rol, r.Nombre as Rol");
		$this->db->from("Usuarios as u");		
		$this->db->join("Roles as r", "u.Id_Rol = r.Id_Rol", "inner");
		$this->db->where("Id_Usuario", $Id_Usuario);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * INSERTA USUARIO
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function INS_Usuario($datarray){
		$this->db->insert("Usuarios", $datarray);
		return $this->db->insert_id();
	}

	/**
	 * ACTUALIZA EN TABLA CLIENTES
	 * @param INT $Id_Usuario IDENTIFICADOR DEL USUARIO
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Usuario($Id_Usuario,$datarray){
		$this->db->where("Id_Usuario", $Id_Usuario);
		$this->db->update("Usuarios", $datarray);
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA USUARIO POR SU ID
	 * @param INT $Id_Usuario IDENTIFICADOR DEL USUARIO
	 */
	function DEL_Usuario($Id_Usuario){
		$this->db->where("Id_Usuario", $Id_Usuario);
		$this->db->delete("Usuarios");
		return $this->db->affected_rows();
	}
}

/* End of file Usuarios_model.php */
/* Location: ./application/models/Usuarios_model.php */