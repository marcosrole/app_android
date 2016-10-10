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

			$id_ins = isset($_GET['id_ins']) ? $_GET['id_ins'] : $flag;

			if($id_ins!=$flag){

				$FROM = "FROM asignarinspector  "					
					. "WHERE id_ins='$id_ins' and finalizado=0 and alarmaTomada=0";

				$query = "SELECT * " . $FROM;

				$rs = mysql_query("SELECT count(*) " . $FROM);
				
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
				$result["descripcion"] = "No existen datos a mostrar";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}
		
		echo json_encode($result);
	?>