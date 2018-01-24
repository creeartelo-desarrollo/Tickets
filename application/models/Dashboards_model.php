<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	  *	CUENTA TICKETS ABIERTOS
	  */
	function COUNT_Tickets_Abiertos(){
		$this->db->select("Id_Ticket");
		$this->db->from("Tickets as t");
		$this->db->where("t.Status <",9);
		return $this->db->count_all_results();
	}
		
	/**
	  *	CONSULTA TICKETS EN PROCESO
	  */
	function CNS_Tickets_En_Proceso(){
		$this->db->select("t.Id_Ticket, t.Num_Seguimiento, e.Razon_Social, COALESCE(u.Nombre, 'Sin asignar') as Usuario, d.Sucursal,
    					   c.Latitud, c.Longitud, t.Status, t.Fecha_Alta");
		$this->db->from("Tickets as t");
		$this->db->join("Ticket_Coordenadas as c", "t.Id_Ticket = c.Id_Ticket", "left");
		$this->db->join("Clientes as e", "t.Id_Cliente = e.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->group_by("t.Id_Ticket");
		$this->db->where("t.Status >",2);
		$this->db->where("t.Status <",9);
		$data = $this->db->get();
		return $data->result_array();
	}

	/**
	  *	CONSULTA SIN ATENDER
	  */
	function CNS_Tickets_Sin_Atender(){
		$this->db->select("t.Id_Ticket, t.Num_Seguimiento, e.Razon_Social, COALESCE(u.Nombre, 'Sin asignar') as Usuario, d.Sucursal,
    					  d.Latitud, d.Longitud,t.Status, t.Fecha_Alta");
		$this->db->from("Tickets as t");
		$this->db->join("Clientes as e", "t.Id_Cliente = e.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->group_by("t.Id_Ticket");
		$this->db->where("t.Status <",3);
		$data = $this->db->get();
		return $data->result_array();
	}

	/**
	  *	CONSULTA ABIERTOS  
	  */
	function CNS_Tickets_Abiertos(){
		$this->db->select("t.Id_Ticket, t.Num_Seguimiento, e.Razon_Social, COALESCE(u.Nombre, 'Sin asignar') as Usuario, d.Sucursal,
    					   c.Latitud, c.Longitud, t.Status, t.Fecha_Alta");
		$this->db->from("Tickets as t");
		$this->db->join("Ticket_Coordenadas as c", "t.Id_Ticket = c.Id_Ticket", "left");
		$this->db->join("Clientes as e", "t.Id_Cliente = e.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->group_by("t.Id_Ticket");
		$this->db->where("t.Status >",2);
		$this->db->where("t.Status <",9);
		$query1 = $this->db->get_compiled_select();

		$this->db->select("t.Id_Ticket, t.Num_Seguimiento, e.Razon_Social, COALESCE(u.Nombre, 'Sin asignar') as Usuario, d.Sucursal,
    					  d.Latitud, d.Longitud,t.Status, t.Fecha_Alta");
		$this->db->from("Tickets as t");
		$this->db->join("Clientes as e", "t.Id_Cliente = e.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->group_by("t.Id_Ticket");
		$this->db->where("t.Status <",3);

		$query2 = $this->db->get_compiled_select();


		$data = $this->db->query($query1 . " UNION " . $query2);
		return $data->result_array();
	}

	/**
	 * CONSULTA SUCURSALES ACTIVAS / INACTIVAS
	 * @param INT $Status STATUS 1/0
	 */
	function CNS_Sucursales($Status){
		$this->db->select("c.Codigo,c.Razon_Social,d.Sucursal,d.Latitud,d.Longitud");
		$this->db->from("Clientes as c");
		$this->db->join("Direcciones as d", "c.Id_Cliente = d.Id_Referencia", "inner");
		$this->db->where("c.Status", $Status);
		$this->db->where("d.Status", $Status);
		$data = $this->db->get();
		return $data->result_array();
	}

	/**
	 * CONSULTA LAS UBICACIONES DE LOS USUARIOS ACTIVOS
	 */
	function CNS_UbicacionUsuarios(){
		$this->db->select("u.Id_Usuario, u.Nombre, c.Latitud, c.Longitud, c.Fecha_Actualiza");
		$this->db->from("Usuarios as u");
		$this->db->join("Ubicacion_Usuarios as c", "u.Id_Usuario = c.Id_Usuario", "inner");
		$this->db->where("u.Status", 1);
		$data = $this->db->get();
		return $data->result_array(); 
	}
}
