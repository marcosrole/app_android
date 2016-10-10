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

				$FROM = "FROM asignarinspector as AI INNER JOIN alarma as A ON AI.id_ala=A.id INNER JOIN tipoalarma as TA ON TA.id=A.id_tipAla INNER JOIN dispositivo as D ON A.id_dis=D.id INNER JOIN histoasignacion as HA ON D.id=HA.id_dis INNER JOIN sucursal as S ON HA.id_suc=S.id INNER JOIN empresa as E on E.cuit=S.cuit_emp INNER JOIN direccion as DIR ON DIR.id=S.id_dir INNER JOIN inspector as INS ON AI.id_ins=INS.id INNER JOIN usuario as USR ON USR.id=INS.id_usr INNER JOIN persona as PER ON USR.dni_per=PER.dni INNER JOIN persona as PERR on S.dni_per=PERR.dni "					
					. "WHERE AI.id='$id_AsiIns'";

				$query = "SELECT  A.id as id_ala, A.id_dis, AI.id_ins, E.cuit, A.fechahs as fecha , A.fechahs as hs, PER.dni as dni_ins, PER.nombre as nombre_ins, PER.apellido as apellido_ins, E.cuit, E.razonsocial, S.nombre as nombre_suc, S.id as id_suc, CONCAT_ws(' ',DIR.calle,DIR.altura, DIR.piso, DIR.depto) AS direccion, PERR.dni as dni_due, PERR.nombre as nombre_due, PERR.apellido as apellido_due, TA.descripcion " . $FROM;

				$rs = mysql_query("SELECT count(*) " . $FROM);
				
			}else{

				
				
			}		
			$row = mysql_fetch_row($rs);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rs = mysql_query($query) or die(mysql_error());
			$rows = array();
			while ($row = mysql_fetch_object($rs)) { //Recupera una fila del objeto $rs
				$fechahs=explode(" ", $row->fecha); 
				$date = date_create($fechahs[0]);
				$row->fecha=date_format($date, 'd-m-Y');
					$hs=$fechahs[1];
				$row->hs=$hs;
				array_push($rows, $row);
			}
			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "No existen alarmas para el inspector";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>