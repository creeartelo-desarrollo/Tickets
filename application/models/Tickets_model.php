<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	  * CUENTA EL TOTAL DE TICKETS EN LA TABLA
	  * @param CHAR $Modulo MODULO DEL QUE SE HACE LA CONSULTA
	  * SI ES A = ABIERTOS = CONSULTA CON EL ESTATUS MENOR A 9
	  * SI ES C = CERRADOS = CONSULTA CON ES ESTATUS IGUAL A 9
	  */
	function COUNT_Tickets($Modulo){
		$this->db->select("Id_Ticket");
		$this->db->from("Tickets");
		
		if($Modulo == "A"){
			$this->db->where("Status <",9);
		}else{
			$this->db->where("Status",9);
		}

		return $this->db->count_all_results();
	}

	/**
	 * CONSULTA TICKETS ABIERTOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 * ALERTAS: 
	 *  1- ===> VERDE
	 *  1+ ===> AMARILLO
	 *  24+ ==> ROJO
	 */
	function CNS_TicketsAbiertos($Skip, $Take, $Order_Field, $Order){
		$this->db->select("t.Id_Ticket, t.Id_Usuario, t.Num_Seguimiento, f.Orden_Servicio,
						   IF(t.Ticket != '', t.Ticket, '-') as Ticket, c.Razon_Social,
						   t.Fecha, t.Fecha_Alta, t.Status, COALESCE(u.Nombre, 'Sin asignar') as Usuario,
						   CASE 
								WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) < 61 THEN '#5cb85c'
						        WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) > 1440 THEN '#d9534f'
								ELSE '#f0ad4e'
						   END AS Alerta,
						   CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta,NOW()), ' hr', ' ',
					       	    MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, NOW()),60), ' min') AS Intervalo");
		$this->db->from("Tickets as t");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente =c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Ticket_Finaliza as f", "t.Id_Ticket = f.Id_Ticket", "left");
		$this->db->where("t.Status <",9);
		$this->db->group_by("t.Id_Ticket");
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA TICKETS ABIERTOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_TicketsAbiertosMatch($Skip, $Take, $Order_Field, $Order, $Match){
		$this->db->select("t.Id_Usuario, t.Num_Seguimiento, f.Orden_Servicio,
						   IF(t.Ticket != '', t.Ticket, '-') as Ticket, c.Razon_Social,
						   t.Fecha,t.Status, COALESCE(u.Nombre, 'Sin asignar') as Usuario,
						   CASE 
								WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) < 61 THEN '#5cb85c'
						        WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) > 1440 THEN '#d9534f'
								ELSE '#f0ad4e'
						    END AS Alerta,
						    CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta,NOW()), ' hr', ' ',
					       	    MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, NOW()),60), ' min') AS Intervalo");
		$this->db->from("Tickets as t");
		$this->db->like("CONCAT(t.Fecha, ' ', u.Nombre, ' ', t.Num_Seguimiento, ' ', c.Razon_Social, ' ', d.Sucursal, ' ', t.Ticket)", $Match);
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente =c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Ticket_Finaliza as f", "t.Id_Ticket = f.Id_Ticket", "left");
		$this->db->where("t.Status <",9);
		
		$this->db->group_by("t.Id_Ticket");
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}


	/**
	 * CONSULTA TICKETS CERRADOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_TicketsCerrados($Skip, $Take, $Order_Field, $Order){
		$this->db->select("t.Id_Ticket, t.Id_Usuario, t.Num_Seguimiento, f.Orden_Servicio,
						   IF(t.Ticket != '', t.Ticket, '-') as Ticket, c.Razon_Social,
						   t.Fecha, t.Fecha_Alta, t.Status, COALESCE(u.Nombre, 'Sin asignar') as Usuario,
						   CASE 
								WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta, f.Fecha_Alta) < 61 THEN '#5cb85c'
						        WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta, f.Fecha_Alta) > 1440 THEN '#d9534f'
								ELSE '#f0ad4e'
						   END AS Alerta,
						   CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta, f.Fecha_Alta), ' hr', ' ',
					       	    MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta,  f.Fecha_Alta),60), ' min') AS Intervalo");
		$this->db->from("Tickets as t");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente =c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Ticket_Finaliza as f", "t.Id_Ticket = f.Id_Ticket", "left");
		$this->db->where("t.Status",9);
		$this->db->group_by("t.Id_Ticket");
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA TICKETS CERRADOS CON PARÁMETRO DE BÚSQUEDA, PAGINACIÓN Y ORDENAMIENTO
	 */
	function CNS_TicketsCerradosMatch($Skip, $Take, $Order_Field, $Order, $Match){
		$this->db->select("t.Id_Usuario, t.Num_Seguimiento, f.Orden_Servicio,
						   IF(t.Ticket != '', t.Ticket, '-') as Ticket, c.Razon_Social,
						   t.Fecha,t.Status, COALESCE(u.Nombre, 'Sin asignar') as Usuario,
						   CASE 
								WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta, f.Fecha_Alta) < 61 THEN '#5cb85c'
						        WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta, f.Fecha_Alta) > 1440 THEN '#d9534f'
								ELSE '#f0ad4e'
						    END AS Alerta,
						    CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta, f.Fecha_Alta), ' hr', ' ',
					       	    MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta,  f.Fecha_Alta),60), ' min') AS Intervalo");
		$this->db->from("Tickets as t");
		$this->db->like("CONCAT(t.Fecha, ' ', u.Nombre, ' ', t.Num_Seguimiento, ' ', c.Razon_Social, ' ', d.Sucursal, ' ', t.Ticket, ' ', f.Orden_Servicio)", $Match);
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente =c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Ticket_Finaliza as f", "t.Id_Ticket = f.Id_Ticket", "left");
		$this->db->where("t.Status",9);		
		$this->db->group_by("t.Id_Ticket");
		$this->db->order_by($Order_Field, $Order);
		$this->db->limit($Take,$Skip);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA EL ÚLTIMO TICKET INSERTADO
	 */
	/**
	 * CONSULTA EL ÚLTIMO TICKET INSERTADO
	 * @param STRING $Orden ORDEN DE LA CONSULTA, 
	 *                      ASCENDENTE O DESCENDETNE
	 */
	function CNS_LastTicket($Orden){
		$this->db->select("Num_Seguimiento,Fecha");
		$this->db->from("Tickets");
		$this->db->order_by("Num_Seguimiento", $Orden);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * CONSULTA UN TICKET POR SI ID
	 * @param INT $Id_Ticket IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_TicketByID($Id_Ticket){
		$this->db->select("t.Id_Ticket,t.Id_Usuario, t.Id_Cliente, t.Id_Direccion, t.Num_Seguimiento, 
						   t.Ticket, c.Razon_Social, d.Sucursal, 
						   CONCAT(d.Calle,' ', d.No_Ext,' ', d.No_Int,' ', d.Colonia,' ', d.Municipio,' ', d.Estado) as Direccion, 
						   t.Descripcion,t.Observaciones, t.Fecha,t.Status, u.Nombre as Usuario");
		$this->db->from("Tickets as t");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente = c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->where("t.Id_Ticket", $Id_Ticket);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * CONSULTA DIAGNOSTICO DE TICKET POR SU ID
	 * @param INT $Id_Ticket IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_TicketDiagnostico($Id_Ticket){
		$this->db->select("Diagnostico,Material");
		$this->db->from("Ticket_Diagnostico");
		$this->db->where("Id_Ticket", $Id_Ticket);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * CONSULTA EVIDENCIA EN IMAGENES POR ID_TICKET
	 * @param INT $Id_Ticket IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_TicketImagenes($Id_Ticket, $Tipo = null){
		$this->db->select("Tipo, Ruta_Imagen");
		$this->db->from("Evidencia_Fotos");
		$this->db->where("Id_Ticket", $Id_Ticket);
		
		#	Si se recibe el tipo de parametro, se filtra
		if($Tipo){
			$this->db->where("Tipo", $Tipo);	
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}	

	/**
	 * CONSULTA TABLA DE TIEMPOS TICKET POR SU ID
	 * @param INT $Id_Ticket IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_TiemposTicket($Id_Ticket){
		$this->db->select("Orden, Tipo, Fecha, Razon");
		$this->db->from("Tiempos_Ticket");
		$this->db->order_by("Orden", "ASC");
		$this->db->where("Id_Ticket", $Id_Ticket);
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA TABLA DE FINALIZA POR SU ID
	 * @param INT $Id_Ticket IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_TicketFinaliza($Id_Ticket){
		$this->db->select("Orden_Servicio, Observaciones");
		$this->db->from("Ticket_Finaliza");
		$this->db->where("Id_Ticket", $Id_Ticket);
		
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * CONSULTA LOS USUARIOS QUE SON JEFES DE CUADRILLA
	 * Y ESTÁN ACTIVOS
	 */
	function CNS_Cuadrillas(){
		$this->db->select("Id_Usuario,Nombre");
		$this->db->from("Usuarios");
		$this->db->where("Status", 1);
		$this->db->where("Id_Rol", 2);
		$query = $this->db->get();
		return $query->result_array();
	}


	/**
	 * CONSULTA CLIENTES
	 */
	function CNS_Clientes(){
		$this->db->select("Id_Cliente, Codigo, Razon_Social");
		$this->db->from("Clientes");
		$this->db->where("Status", 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * CONSULTA LAS SUCURSALES DE UN CLIENTE
	 * @param INT $Id_Cliente IDENTIFICADOR DEL CLIENTE
	 */
	function CNS_DireccionesCliente($Id_Cliente = null){
		$this->db->select("Id_Direccion, Sucursal");
		$this->db->from("Direcciones");

		#	Si se recibe el Id_Cliente, se filtra
		if($Id_Cliente){
			$this->db->where("Id_Referencia", $Id_Cliente);
		}
	
		$this->db->where("Status", 1);
		$query = $this->db->get();
		return $query->result_array();
	}


	/**
	 * CONSULTA UN USUARIO POR SU ID
	 * @param INT $Id_Usuario IDENTIFICADOR DEL USUARIO
	 */
	function CNS_UsuarioByID($Id_Usuario){
		$this->db->select("u.Id_Usuario, u.Nombre, u.Usuario, u.Ruta_Imagen,u.TokenFCM, r.Nombre as Rol");
		$this->db->from("Usuarios as u");
		$this->db->join("Roles as r", "u.Id_Rol = r.Id_Rol", "inner");
		$this->db->where("u.Id_Usuario", $Id_Usuario);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * CONSULTA UNA DIRECCION POR SI ID
	 * @param INT $Id_Direccion IDENTIFICADOR DEL REGISTRO
	 */
	function CNS_DireccionByID($Id_Direccion){
		$this->db->select("Id_Direccion, Sucursal, Id_Referencia, Calle, No_Ext, No_Int, Colonia,
						   Municipio, Estado, Codigo_Postal");
		$this->db->from("Direcciones");
		$this->db->where("Id_Direccion", $Id_Direccion);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * CONSULTA TICKETS CON FILTROS DE FECHAS Y STATUS
	 * @param [DATE] $Fecha_Inicio [RANGO INICIAL DE LA FECHAS]
	 * @param [DATE] $Fecha_Fin    [RANGO INICIAL DE LA FECHAS]
	 * @param [ARRAY] $Filtros     [ARRAY DE STATUS QUE SE VAN A CONSULTAR [a]|[c]]
	 */
	function CNS_TicketsFiltros($Fecha_Inicio,$Fecha_Fin,$Filtros)
	{
		$this->db->select("t.Id_Usuario, t.Num_Seguimiento, f.Orden_Servicio, t.Fecha_Alta, t.Status,
    					   IF(t.Ticket != '', t.Ticket, '-') AS Ticket, c.Razon_Social, d.Sucursal,
    					   CONCAT(d.Calle, ' ', d.No_Ext, ' ', d.No_Int, ' ', d.Colonia, ' ', d.Estado, ' ', d.Codigo_Postal) as Direccion,
    					   COALESCE(u.Nombre, 'Sin asignar') AS Usuario,
    					   IF(f.Fecha_Alta IS NULL,
							 CASE 
								WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) < 61 THEN '#5cb85c'
							    WHEN TIMESTAMPDIFF(MINUTE,t.Fecha_Alta,NOW()) > 1440 THEN '#d9534f'
								ELSE '#f0ad4e'
							END,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, f.Fecha_Alta) < 61 THEN '#5cb85c'
								WHEN TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, f.Fecha_Alta) > 1440
								THEN '#d9534f' ELSE '#f0ad4e'
							END) AS Alerta,
							IF(f.Fecha_Alta IS NULL,
								CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta,NOW()), ' hr', ' ',
						     	MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, NOW()),60), ' min'),
						        CONCAT(TIMESTAMPDIFF(HOUR,t.Fecha_Alta, f.Fecha_Alta), ' hr', ' ',
								MOD(TIMESTAMPDIFF(MINUTE, t.Fecha_Alta, f.Fecha_Alta),60), ' min')		
						    ) as Intervalo");
		$this->db->from("Tickets as t");
		$this->db->join("Usuarios as u", "t.Id_Usuario = u.Id_Usuario", "left");
		$this->db->join("Clientes as c", "t.Id_Cliente =c.Id_Cliente", "left");
		$this->db->join("Direcciones as d", "t.Id_Direccion = d.Id_Direccion", "left");
		$this->db->join("Ticket_Finaliza as f", "t.Id_Ticket = f.Id_Ticket", "left");

		$this->db->where("t.Fecha >=", $Fecha_Inicio);
		$this->db->where("t.Fecha <=", $Fecha_Fin);

		# si en el array de filtros falta alguna de las dos variables ('a' o 'c')
		# se aplican los filtros recibidos
		if(!(in_array("a", $Filtros) && in_array("c", $Filtros))){
			# filtra por tickets abiertos
			if(in_array("a", $Filtros)){
				$this->db->where("t.Status <", 9);
			# filtra por tickets cerrados
			}elseif(in_array("c", $Filtros)){
				$this->db->where("t.Status", 9);
			}
		}

		$this->db->group_by("t.Id_Ticket");
		$this->db->order_by("t.Fecha", "ASC");
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * ACTUALIZA EN TABLA TICKETS
	 * @param ID $Id_Ticket IDENTIFICADOR DEL TICKET
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Ticket($Id_Ticket, $dataarray){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->update("Tickets", $dataarray);
		return $this->db->affected_rows();
	}

	/**
	 * ACTUALIZA TICKET DIAGNOSTICO
	  * @param ID $Id_Ticket IDENTIFICADOR DEL TICKET
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Ticket_Diagnostico($Id_Ticket,$dataarray){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->update("Ticket_Diagnostico", $dataarray);
		return $this->db->affected_rows();
	}

	/**
	 * ACTUALIZA TICKET FINALIZA
	  * @param ID $Id_Ticket IDENTIFICADOR DEL TICKET
	 * @param ARRAY $dataarray ARRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function UPD_Ticket_Finaliza($Id_Ticket,$dataarray){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->update("Ticket_Finaliza", $dataarray);
		return $this->db->affected_rows();
	}

	/**
	 * INSERTA TICKET
	 * @param ARRAY $dataarray RRAY ASOCIATIVO CON 
	 * LOS DATOS A GUARDAR
	 */
	function INS_Ticket($dataarray){
		$this->db->insert("Tickets", $dataarray);
		return $this->db->insert_id();
	}

	/**
	 * ELIMINA EN TABLA Evidencia_Fotos 
	 * REGISTROS DEL TICKET
	 */
	function DEL_EvidenciaFotos($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Evidencia_Fotos");
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA EN TABLA Tiempos_Ticket 
	 * REGISTROS DEL TICKET
	 */
	function DEL_TiemposTicket($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Tiempos_Ticket");
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA EN TABLA Ticket_Diagnostico 
	 * REGISTRO DEL TICKET
	 */
	function DEL_DiagnosticoTicket($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Ticket_Diagnostico");
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA EN TABLA Ticket_Diagnostico 
	 * REGISTRO DEL TICKET
	 */
	function DEL_CoordenadasTicket($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Ticket_Coordenadas");
		return $this->db->affected_rows();
	}

	/**
	 * ELIMINA EN TABLA Ticket_Finaliza 
	 * REGISTRO DEL TICKET
	 */
	function DEL_FinalizaTicket($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Ticket_Finaliza");
		return $this->db->affected_rows();
	}	

	/**
	 * ELIMINA EN TABLA Tickets EL REGISTRO
	 */
	function DEL_Ticket($Id_Ticket){
		$this->db->where("Id_Ticket", $Id_Ticket);
		$this->db->delete("Tickets");
		return $this->db->affected_rows();
	}		
}
