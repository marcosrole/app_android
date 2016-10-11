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
				$query = " SELECT AC.observacion, AC.fechahs as fecha, AC.fechahs as hs, TA.nombre as nom_TipAla, CONCAT_ws(' ',DIR.calle,DIR.altura, DIR.piso, DIR.depto) AS direccion, SUC.nombre as nom_suc, EMP.razonsocial as emp_razsoc FROM acta as AC INNER JOIN alarma as AL ON AC.id_ala = AL.id INNER JOIN tipoalarma as TA ON TA.id=AL.id_tipAla INNER join sucursal as SUC on SUC.id=AC.id_suc INNER JOIN empresa as EMP on EMP.cuit=SUC.cuit_emp INNER JOIN direccion as DIR on DIR.id=SUC.id_dir where AC.id_ins='$id_ins'";
				$rs = mysql_query(" SELECT count(*) FROM acta as AC INNER JOIN alarma as AL ON AC.id_ala = AL.id INNER JOIN tipoalarma as TA ON TA.id=AL.id_tipAla INNER join sucursal as SUC on SUC.id=AC.id_suc INNER JOIN empresa as EMP on EMP.cuit=SUC.cuit_emp INNER JOIN direccion as DIR on DIR.id=SUC.id_dir where AC.id_ins='$id_ins'");
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
				$result["descripcion"] = "No existen actas realizadas por el inspector";
			}

		} catch (exception $e) {
			$result["error"] = -400;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>