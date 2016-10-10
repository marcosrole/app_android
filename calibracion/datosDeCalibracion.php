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


			$id_dis = isset($_GET['id_dis']) ? $_GET['id_dis'] : $flag;
			$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : $flag;
			
			date_default_timezone_set('America/Buenos_Aires');			 
			$fechaHOY = date("Y-m-d H:i:s");
			
			if($id_dis!=$flag && $cantidad!=$flag ){				
				$query = "SELECT db, distancia, fechahs, TIMESTAMPDIFF(SECOND, fechahs, '$fechaHOY') as diferencia from detalle_dispo WHERE id_dis='$id_dis' ORDER BY fechahs DESC limit " . (int)$cantidad;
				$rs = mysql_query("SELECT count(*) from detalle_dispo WHERE id_dis='$id_dis' ORDER BY fechahs DESC limit " . (int)$cantidad);
			}
			
			$row = mysql_fetch_row($rs);			
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			
			$cantidad=0;
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				array_push($rows, $row);
				$cantidad++;
			}
			$result["total"] = $cantidad;
			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se puede acceder a los datos del dispositivo";
			}

		} catch (exception $e) {
			$result["error"] = -400;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>