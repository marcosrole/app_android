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

			if($id_dis!=$flag){
				$query = "SELECT db, distancia, fechahs as hs, fechahs as fecha, id_dis "
				. "FROM detalle_dispo "					
					. "WHERE (id_dis='$id_dis') ORDER BY fechahs DESC LIMIT 20 ";

				$rs = mysql_query("SELECT COUNT(*) "
				. "FROM detalle_dispo "					
					. "WHERE (id_dis='$id_dis') ORDER BY fechahs DESC LIMIT 20 ");
			}
			
			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				$fechahs=explode(" ", $row->fecha); 
				$date = date_create($fechahs[0]);
				$row->fecha=date_format($date, 'd/m/Y');
					$hs=$fechahs[1];
				$row->hs=$hs;
				array_push($rows, $row);
			}
			$rows=array_reverse($rows);
			$result["total"]=count($rows);

			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se encuentran detalle del dispositivo: " . $id_dis;
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}


	echo json_encode($result);
	?>