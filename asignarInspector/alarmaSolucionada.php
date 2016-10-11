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
			
			$id_AsiIns = isset($_GET['id_AsiIns']) ? $_GET['id_AsiIns'] : $flag;
			

			if($id_AsiIns!=$flag){
				$query = "UPDATE asignarinspector SET finalizado=1, alarmaTomada=1 WHERE id='$id_AsiIns'";
				$query1 = "UPDATE alarma SET solucionado=1 WHERE id=(SELECT id_ala FROM asignarinspector WHERE id='$id_AsiIns')";
				$query2 = "SELECT finalizado from asignarinspector WHERE id='$id_AsiIns'";
				$rs = mysql_query("SELECT count(*) from asignarinspector WHERE id='$id_AsiIns'");
			}
			
			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rs = mysql_query($query1) or die(mysql_error());
			$rs = mysql_query($query2) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				array_push($rows, $row);
			}
			$result["rows"] = $rows;
			
			
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se ha podido establecer como Solucionado";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>