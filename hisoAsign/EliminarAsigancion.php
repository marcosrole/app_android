<?php
	try {
			$conexion=include ("../db_connect.php");
			$db = new DB_CONNECT();
			 date_default_timezone_set('America/Buenos_Aires');
			$result = array();
			$result["error"] = 0;
			$result["descripcion"] = '';
			$result["total"] = 10;
			$result["rows"] = array();
			$rows_reg = 10;
			$query = ' ';

			$flag=-1;
			$fechaHOY = date("Y-m-d");

			$id_dis = isset($_GET['id_dis']) ? $_GET['id_dis'] : $flag;

			if($id_dis!=$flag){
				$rs = mysql_query("SELECT id  FROM histoasignacion WHERE id_dis='$id_dis' and fechaBaja='1900-01-01'");
				$row = mysql_fetch_row($rs);
				$query = "UPDATE histoasignacion SET fechaModif='$fechaHOY',fechaBaja='$fechaHOY',observacion='Registro Eliminado' WHERE id='$row[0]' ";
				mysql_query($query);
				$query = "UPDATE dispositivo SET disponible='1' WHERE id='$id_dis' ";
				mysql_query($query);
				$query = "DELETE FROM calibracion WHERE id_AsiDIs='$row[0]' ";
				mysql_query($query);

				$rs = mysql_query("SELECT count(*)  FROM histoasignacion WHERE id_dis='$id_dis' and fechaBaja='$fechaHOY'");							
				
			}	

			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];	
			
			if($result["total"]<=0){
				$result["error"] = -1;
				$result["descripcion"] = "PHP ERROR. No se pudo eliminar el registro seleccionado.";
			}		

		} catch (exception $e) {
			$result["error"] = -2;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>