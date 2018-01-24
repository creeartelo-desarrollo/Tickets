<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	  *	CUENTA EL TOTAL DE CLIENTES EN LA TABLA
	  */
	function COUNT_Clientes(){
		$this->db->select("Id_Cliente");
		$this->db->from("Clientes");
		return $this->db->count_all_results();
	}

	/**
	 * CONSULTA CLIENTES CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_Clientes($Skip, $Take, $Order_Field, $Order){
		$this->db->select("c.Id_Cliente, c.Codigo, c.Razon_Social, c.Logo, 
						   c.Status, COUNT(d.Id_Direccion) as Num_Sucursales");
		$this->db->from("Clientes as c");
		$this->db->join("Direcciones as d", "c.Id_Cliente = d.Id_Referencia", "left");
		$this->db->order_by($Order_Field, $Order);
		$this->db->group_by("c.Id_Cliente");
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA CLIENTES CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_ClientesMatch($Skip, $Take, $Order_Field, $Order, $Match){
		$this->db->select("c.Id_Cliente, c.Codigo, c.Razon_Social, c.Logo, 
						   c.Status, COUNT(d.Id_Direccion) as Num_Sucursales");
		$this->db->from("Clientes as c");
		$this->db->join("Direcciones as d", "c.Id_Cliente = d.Id_Referencia", "left");
		$this->db->like("CONCAT(c.Codigo, ' ', c.Razon_Social, ' ', c.Nombre_Comercial)", $Match);
		$this->db->order_by($Order_Field, $Order);
		$this->db->group_by("c.Id_Cliente");
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA UN CLIENTE POR SI ID
	 * @param INT $Id_Cliente IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_ClienteByID($Id_Cliente){
		$this->db->select("Id_Cliente, Codigo, Razon_Social, 
						   Logo, Status");
		$this->db->from("Clientes");
		$this->db->where("Id_Cliente", $Id_Cliente);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * INSERTA CLIENTE
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function INS_Cliente($datarray){
		$this->db->insert("Clientes", $datarray);
		return $this->db->insert_id();
	}

	/**
	 * ACTUALIZA EN TABLA CLIENTES
	 * @param INT $Id_Cliente IDENTIFICADOR DEL CLIENTE
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Cliente($Id_Cliente,$datarray){
		$this->db->where("Id_Cliente", $Id_Cliente);
		$this->db->update("Clientes", $datarray);
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA CLIENTE POR SU ID
	 * @param INT $Id_Cliente IDENTIFICADOR DE LA DIRECCIÓN
	 */
	function DEL_Cliente($Id_Cliente){
		$this->db->where("Id_Cliente", $Id_Cliente);
		$this->db->delete("Clientes");
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA DIRECCIÓN POR ID CLIENTE
	 * @param INT $Id_Direccion IDENTIFICADOR DEL CLIENTE
	 */
	function DEL_DireccionByCliente($Id_Cliente){
		$this->db->where("Id_Referencia", $Id_Cliente);
		$this->db->delete("Direcciones");
		return $this->db->affected_rows();
	}

	/**
	 * CONSULTA LAS SUCURSALES DE UN CLIENTE
	 * @param INT $Id_Cliente IDENTIFICADOR DEL CLIENTE
	 */
	function CNS_SucursalesCliente($Id_Cliente){
		$this->db->select("Id_Direccion, Id_Referencia, Sucursal, Status");
		$this->db->from("Direcciones");
		$this->db->where("Id_Referencia", $Id_Cliente);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA UNA SUCURSAL POR SI ID
	 * @param INT $Id_Direccion IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_SucursalByID($Id_Direccion){
		$this->db->select("Id_Direccion, Sucursal, Id_Referencia, Calle, No_Ext, No_Int, Colonia,
						   Municipio, Estado, Codigo_Postal, Latitud, Longitud, Status");
		$this->db->from("Direcciones");
		$this->db->where("Id_Direccion", $Id_Direccion);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * INSERTA DIRECCION
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function INS_Direccion($datarray){
		$this->db->insert("Direcciones", $datarray);
		return $this->db->insert_id();
	}

	/**
	 * ACTUALIZA DIRECCION
	 * @param INT $Id_Direccion IDENTIFICADOR DE LA DIRECCIÓN
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Direccion($Id_Direccion,$datarray){
		$this->db->where("Id_Direccion", $Id_Direccion);
		$this->db->update("Direcciones", $datarray);
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA DIRECCIÓN POR SU ID
	 * @param INT $Id_Direccion IDENTIFICADOR DE LA DIRECCIÓN
	 */
	function DEL_Direccion($Id_Direccion){
		$this->db->where("Id_Direccion", $Id_Direccion);
		$this->db->delete("Direcciones");
		return $this->db->affected_rows();
	}

}
