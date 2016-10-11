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
			$id_dis = isset($_GET['id_dis']) ? $_GET['id_dis'] : $flag;
			$coordLat = isset($_GET['coordLat']) ? $_GET['coordLat'] : $flag;
			$coordLon = isset($_GET['coordLon']) ? $_GET['coordLon'] : $flag;
			$id_suc = isset($_GET['id_suc']) ? $_GET['id_suc'] : $flag;

			if($id_dis!=$flag && $coordLat!=$flag && $coordLon!=$flag && $id_suc!=$flag){
				$query = "INSERT INTO histoasignacion(fechaAlta, fechaModif, fechaBaja, coordLat, coordLon, observacion, id_dis, id_suc) VALUES ('$fechaHOY',null,'1900-01-01','$coordLat','$coordLon','Nueva asigancion realizada','$id_dis','$id_suc')";				
				$queryDispositivo = "UPDATE dispositivo SET disponible='0' WHERE id='$id_dis' ";
			}else{

			}		
			mysql_query($queryDispositivo);
			$result["total"] = 1;
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			
			
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se ha podido realizar la asignacion";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>