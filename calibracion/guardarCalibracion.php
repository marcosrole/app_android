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
			 date_default_timezone_set('America/Buenos_Aires');
			 
			$flag=-1;
			$fechaHOY = date("Y-m-d");
			$id_AsiDis = isset($_GET['id_AsiDis']) ? $_GET['id_AsiDis'] : $flag;
			$db_permitido = isset($_GET['db_permitido']) ? $_GET['db_permitido'] : $flag;
			$dist_permitido = isset($_GET['dist_permitido']) ? $_GET['dist_permitido'] : $flag;
			

			if($id_AsiDis!=$flag && $db_permitido!=$flag && $dist_permitido!=$flag){
				$pregunto = "SELECT * FROM histoasignacion as HA INNER JOIN calibracion as CAL ON HA.id=CAL.id_AsiDis WHERE (HA.id='$id_AsiDis' and HA.fechaBaja='1900-01-01')";
				$pregunto_rs = mysql_query(" SELECT count(*) FROM histoasignacion as HA INNER JOIN calibracion as CAL ON HA.id=CAL.id_AsiDis WHERE (HA.id='$id_AsiDis' and HA.fechaBaja='1900-01-01')");
				
				$query_insert = "INSERT INTO calibracion(db_permitido, dist_permitido, fecha, id_AsiDis) VALUES ('$db_permitido','$dist_permitido','$fechaHOY','$id_AsiDis')";								
				$query_update = "UPDATE calibracion SET db_permitido='$db_permitido', dist_permitido='$dist_permitido', fecha='$fechaHOY', id_AsiDis='$id_AsiDis' WHERE id_AsiDis='$id_AsiDis'";
				
			}
			
			
			$row = mysql_fetch_row($pregunto_rs);
			$result["total"] = $row[0];
			
			if($result["total"]!=0){
				$rs = mysql_query($query_update) or die(mysql_error());
				$result["total"] = 1;
				$rows = array();
			}else {
				$rs = mysql_query($query_insert) or die(mysql_error());
				$result["total"] = 1;
				$rows = array();
			}
			
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se ha podido realizar la asignacion";
			}

		} catch (exception $e) {
			$result["error"] = -400;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>