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

				$FROM = "FROM asignarinspector as AI INNER JOIN alarma as A ON AI.id_ala=A.id INNER JOIN tipoalarma as TA ON TA.id=A.id_tipAla INNER JOIN dispositivo as D ON A.id_dis=D.id INNER JOIN histoasignacion as HA ON D.id=HA.id_dis INNER JOIN sucursal as S ON HA.id_suc=S.id INNER JOIN empresa as E on E.cuit=S.cuit_emp INNER JOIN direccion as DIR ON DIR.id=S.id_dir "					
					. "WHERE AI.id_ins='$id_ins' and AI.finalizado=0 and HA.fechaBaja='1900-01-01'";

				$query = "SELECT AI.id as id_AsignarInspector , A.id_dis, AI.id_ins, AI.observacion, A.fechahs as hs, A.fechahs as fecha, TA.descripcion, S.nombre as sucursal, E.razonsocial as empresa, CONCAT_ws(' ',DIR.calle,DIR.altura, DIR.piso, DIR.depto) AS direccion " . $FROM;

				$rs = mysql_query("SELECT count(*) " . $FROM);
				
			}else{

				$FROM = "FROM asignarinspector as AI INNER JOIN alarma as A ON AI.id_ala=A.id INNER JOIN tipoalarma as TA ON TA.id=A.id_tipAla INNER JOIN dispositivo as D ON A.id_dis=D.id INNER JOIN histoasignacion as HA ON D.id=HA.id_dis INNER JOIN sucursal as S ON HA.id_suc=S.id INNER JOIN empresa as E on E.cuit=S.cuit_emp INNER JOIN direccion as DIR ON DIR.id=S.id_dir "					
					. " ";

				$query = "SELECT AI.observacion, A.fechahs, TA.descripcion, S.nombre as sucursal, E.razonsocial as empresa, CONCAT_ws(' ',DIR.calle,DIR.altura, DIR.piso, DIR.depto) AS direccion " . $FROM;

				$rs = mysql_query("SELECT count(*) " . $FROM);
				
			}	


			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				$fechahs=explode(" ", $row->hs); 
				$date = date_create($row->fecha);
				$row->fecha=date_format($date, 'd/m/Y');
					$hs=$fechahs[1];
				$row->hs=$hs;

				$charset='ISO-8859-1'; // o 'UTF-8'
				$str = iconv($charset, 'ASCII//TRANSLIT', $row->observacion);
				$row->observacion = preg_replace("/[^A-Za-z0-9 ]/", '', $str);				
				
				
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