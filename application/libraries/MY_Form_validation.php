<?php
class MY_Form_validation extends CI_Form_validation {

	public function __construct($rules = array())
	{
	    parent::__construct($rules);
	}

	/**
	 * VALIDA QUE EL FORMATO DE UNA FECHA SE EL CORRECTO PARA MYSQL
	 * @param  DATE $date FECHA A VALIDAR
	 * @return [BOOLEAN]       RESULTADO DE LA VALIDACIÓN
	 */
	public function valid_date($date)
	{
	    $d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') === $date;
	}

	public function is_unique($str, $strparms)
	{
		# parametros ingresados en la validación, separados por "."
		# se hace un explode para convertirlos en array y guardarlos en variables
		# param0 es la tabla
		# param1 es el campo que debe ser unico
		$parms    	   = explode(".",$strparms);			
		$tabla 		   = $parms[0];
		$campo		   = $parms[1];

		# se obtiene el nombre de la primery key de la tabla
		if($pk = $this->getPK($tabla)){
			# consulta si hay un registro en la tabla con el mismo valor
			$queryuq = $this->CI->db->select($pk)
					   ->from($tabla)
					   ->where($campo,$str);
			
			# en caso de ser un update
			# si se recibio el parametro2  que es el id del regisro
			# se agrega la excepcion a la consulta
			if($parms[2] != ""){
				$queryuq->where("$pk !=",$parms[2]);
			}

			$queryuq->limit(1);

			# si ha retrornado un resultado, la validación será falsa
			$res = $queryuq->get()->row();
			if($res){
				return false;
			}					   
		}
	}

	/**
	 * OBTIENE LA LLAVE PRIMARIA DE UNA TABLA
	 * @param  STRING $tabla TABLA DE LA QUE SE DESEA OBTENER EL PK
	 * @return STRING        PRIMARY KEY DE LA TABLA
	 */
	private function getPK($tabla){
		$query = $this->CI->db->query("SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY'");	
		$query = $query->row();
		return $query->Column_name;
	}

	/**
	 * VALIDA QUE UNA FECHA SEA MAYOR A LA OTRA
	 * @param  DATE     $fmayor FECHA MENOR
	 * @param  DATE     $fmayor NOMBRE DEL CAMPO CON EL QUE SE VA A COMPARAR
	 * @return boolean       RESULTADO DE LA VALIDACIÓN
	 */
	public function is_date_greather_than($fmayor,$postfmenor){
		# Crea fecha del campo recibido
		$Fecha_Inicio = new DateTime($fmayor);
		# Obtiene los datos del campo recibido en el segundo parametro
		$datapostmenor  = $this->CI->form_validation->_field_data[$postfmenor];
		# Crea fecha del valor del segundo campo 
		$Fecha_Fin = new DateTime($datapostmenor["postdata"]);
		# Establece el error
		$this->CI->form_validation->set_message("is_date_greather_than", "The %s fecha field must be greater than " . $datapostmenor["label"]);
		
		//Regresa resultado
		return $Fecha_Inicio > $Fecha_Fin;
	}
}
?>