<?php
	try {
			$conexion=include ("../db_connect.php");
			$db = new DB_CONNECT();
			 
			$result = array();
			$result["error"] = 0;
			$result["descripcion"] = '';
			$result["total"] = 10;
			$result["rows"] = array();
			$rows_reg = 10;
			$query = ' ';

			$flag=-1;

			$name = isset($_GET['name']) ? $_GET['name'] : $flag;
			 $first=(substr($_GET['pass'], 0,1));
             $second=(substr($_GET['pass'],-1));
			$pass = isset($_GET['pass']) ? crypt($_GET['pass'],$first.$second) : $flag;


			if($name==$flag && $pass==$flag){
				$query = " SELECT USR.id, name,pass, INS.id as id_ins FROM usuario as USR INNER JOIN inspector as INS ON USR.id=INS.id_usr ";
				$rs = mysql_query(" SELECT count(*) FROM usuario as USR INNER JOIN inspector as INS ON USR.id=INS.id_usr ");
			}else{
				$query = " SELECT USR.id, name,pass, INS.id as id_ins, PER.nombre FROM usuario as USR INNER JOIN inspector as INS ON USR.id=INS.id_usr  INNER JOIN persona as PER on PER.dni=USR.dni_per WHERE (USR.name='$name') AND (USR.pass='$pass')";
				$rs = mysql_query(" SELECT count(*) FROM usuario as USR INNER JOIN inspector as INS ON USR.id=INS.id_usr where USR.name='$name' AND USR.pass='$pass' ");
			}			
			
			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				array_push($rows, $row);
			}
			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "Usuario(s) no encontrado(s)";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>