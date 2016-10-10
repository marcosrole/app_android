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

			$id_suc = isset($_GET['id_suc']) ? $_GET['id_suc'] : $flag;

			if($id_suc!=$flag){
				$query = "SELECT DIS.id as id_dis, HA.fechaAlta as fechaAlta "
				. "FROM dispositivo as DIS INNER JOIN histoasignacion as HA on DIS.id=HA.id_dis "					
					. "WHERE (DIS.disponible='0') and (HA.id_suc='$id_suc' and HA.fechaBaja='1900-01-01') ORDER BY HA.fechaAlta DESC ";

				$rs = mysql_query("SELECT COUNT(*) "
				. "FROM dispositivo as DIS INNER JOIN histoasignacion as HA on DIS.id=HA.id_dis "					
					. "WHERE (DIS.disponible='0') and (HA.id_suc='$id_suc' and HA.fechaBaja='1900-01-01') ORDER BY HA.fechaAlta DESC ");
			}
			
			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				$date = date_create($row->fechaAlta);
				$row->fechaAlta=date_format($date, 'd/m/Y');
				array_push($rows, $row);
			}
			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No se encuentran detalle del dispositivo ";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>