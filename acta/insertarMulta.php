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
			$fechaHOY = date("Y-m-d H:i:s");
			$id_ins = isset($_GET['id_ins']) ? $_GET['id_ins'] : $flag;
			$id_suc = isset($_GET['id_suc']) ? $_GET['id_suc'] : $flag;
			$id_ala = isset($_GET['id_ala']) ? $_GET['id_ala'] : $flag;
			$observacion = isset($_GET['observacion']) ? $_GET['observacion'] : $flag;


			if($id_ins!=$flag && $id_suc!=$flag && $id_ala!=$flag){
				$query = "INSERT INTO acta(observacion, fechahs, id_ins, id_suc, id_ala) VALUES ('$observacion','$fechaHOY','$id_ins','$id_suc','$id_ala')";				
			}else{

			}		
			
			$result["total"] = 1;
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			
			
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se ha podido almacenar en la base de datos";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>