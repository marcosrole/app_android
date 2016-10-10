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

			$id_Ini=1;
			$id_Fin=1;

			$flag=-1;

			$id_dis = isset($_GET['id_dis']) ? $_GET['id_dis'] : $flag;
			$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : $flag;
			$hsIni = isset($_GET['hsIni']) ? $_GET['hsIni'] : $flag;
			$hsFin = isset($_GET['hsFin']) ? $_GET['hsFin'] : $flag;

			//Paso las horas a segundo
/*
			$hsIni="22:23:00";
			$hsFin="23:50:00";
			$fecha="05/05/2016";
			$id_dis="358";

*/


			$hsIniArray = explode(":", $hsIni);
			$hsIni=$hsIniArray[0]*3600+$hsIniArray[1]*60+$hsIniArray[2]; 
			

			$hsFinArray = explode(":", $hsFin);
			$hsFin=$hsFinArray[0]*3600+$hsFinArray[1]*60+$hsFinArray[2];


			$fechaArray = explode("/", $fecha);
			$diaIni=$fechaArray[0];
			$mesIni=$fechaArray[1];
			$anioIni=$fechaArray[2];

			$diaFin=$diaIni;
			$mesFin=$mesIni;
			$anioFin=$anioIni;


			if($hsFinArray[0]<$hsIniArray[0]){// Si la hora fin es del dia siguiente
				$fechaaux = $fechaArray[2] . "-" . $fechaArray[1] . "-" . $fechaArray[0];
				$nuevafecha = date('Y-m-d', strtotime("$fechaaux + 1 day"));

				$fechaArray = explode("-", $nuevafecha);
				$diaFin=$fechaArray[2];
				$mesFin=$fechaArray[1];
				$anioFin=$fechaArray[0];

			}

			if($id_dis!=$flag){
				$queryIni = "SELECT * from " 
				. "(select id, id_dis, (HOUR(CAST(fechahs AS time)) *3600 "
					. " + MINUTE(CAST(fechahs AS time)) * 60 "
					. " + SECOND(CAST(fechahs AS time))-'$hsIni') as diferencia, "
                     .  " DAYOFMONTH(CAST(fechahs as date)) AS dia, "
                     .  " MONTH(CAST(fechahs as date)) as mes, "
                     .  " YEAR(CAST(fechahs as date)) as anio from detalle_dispo ) as temporal " 
                        . " where diferencia >=0 and id_dis='$id_dis' and dia='$diaIni' and mes='$mesIni' and anio='$anioIni'";

				$rsIni = mysql_query("SELECT count(*) from " 
				. "(select id, id_dis, (HOUR(CAST(fechahs AS time)) *3600 "
					. " + MINUTE(CAST(fechahs AS time)) * 60 "
					. " + SECOND(CAST(fechahs AS time))-'$hsIni') as diferencia, "
                     .  " DAYOFMONTH(CAST(fechahs as date)) AS dia, "
                     .  " MONTH(CAST(fechahs as date)) as mes, "
                     .  " YEAR(CAST(fechahs as date)) as anio from detalle_dispo ) as temporal " 
                        . " where diferencia >=0 and id_dis='$id_dis'and dia='$diaIni' and mes='$mesIni' and anio='$anioIni'");

				$queryFin = "select  D.id, D.id_dis, "
				 . " ((HOUR(CAST(D.fechahs AS time)) *3600 + MINUTE(CAST(D.fechahs AS time)) * 60 + SECOND(CAST(D.fechahs AS time))-'$hsFin')) as diferencia "
				 . " from detalle_dispo as D where D.id_dis='$id_dis' and "
				. " ((HOUR(CAST(D.fechahs AS time)) *3600 + MINUTE(CAST(D.fechahs AS time)) * 60 + SECOND(CAST(D.fechahs AS time))-'$hsFin')) < 0  "
					 . " and DAYOFMONTH(CAST(fechahs as date))='$diaFin' "
					 . " and MONTH(CAST(fechahs as date))='$mesFin' "
					 . " and YEAR(CAST(fechahs as date))='$anioFin'";

				$rsFin = mysql_query("SELECT count(*)  " 
				. " from detalle_dispo as D where D.id_dis='$id_dis' and "
				. " ((HOUR(CAST(D.fechahs AS time)) *3600 + MINUTE(CAST(D.fechahs AS time)) * 60 + SECOND(CAST(D.fechahs AS time))-'$hsFin')) < 0  "
					 . " and DAYOFMONTH(CAST(fechahs as date))='$diaFin' "
					 . " and MONTH(CAST(fechahs as date))='$mesFin' "
					 . " and YEAR(CAST(fechahs as date))='$anioFin'");

				
			}
			
			$row = mysql_fetch_row($rsIni);
			$result["total"] = $row[0];
			$rows_reg = $result["total"];
			$rsIni = mysql_query($queryIni) or die(mysql_error());
			
			if(mysql_fetch_assoc($rsIni)['diferencia']>1800){//Si es mayor a media hs 
				$result["error"] = -1;
				$result["descripcion"] = "No existen detalles para el rango indicado.";
			} 

			if($result["error"]==0){
				$id_Ini = mysql_result($rsIni,0);
			}
			
			
			if(mysql_fetch_row($rsFin)[0]==0){
				$result["error"] = -1;
				$result["descripcion"] = "No existen detalles para el rango indicado.";
			}else{
				$row = mysql_fetch_row($rsFin);
				$result["total"] = $row[0];
				$rows_reg = $result["total"];
				$rsFin = mysql_query($queryFin) or die(mysql_error());
				$rows = array();
				
				$id_max=mysql_fetch_object($rsFin);

				while ($row = mysql_fetch_object($rsFin)) { //Recupera una fila del objeto $rs				
					if($row->diferencia>$id_max->diferencia){
						$id_max=$row;
					}
					
				}
				$id_Fin=$id_max->id;				
			}
			
/*
			if($result["error"]==0 && mysql_fetch_assoc($rsFin)['diferencia']>1800){//Si es mayor a media hs 
				$result["error"] = -1;
				$result["descripcion"] = "No existen detalles para el rango indicado.";
			} 
			//var_dump(mysql_fetch_assoc($rsFin)); die();
			if($result["error"]==0){
				$id_Fin = mysql_result($rsFin,0);
			}	
*/			

			if($result["error"]==0){
					if($id_dis!=$flag){
					$query = "SELECT db, distancia, fechahs as hs, fechahs as fecha, id_dis "
					. "FROM detalle_dispo "					
						. "WHERE (id_dis='$id_dis') and id BETWEEN '$id_Ini' and '$id_Fin' ORDER BY fechahs ASC ";

					$rs = mysql_query("SELECT COUNT(*) "
					. "FROM detalle_dispo "					
						. "WHERE (id_dis='$id_dis') and id BETWEEN '$id_Ini' and '$id_Fin' ORDER BY fechahs ASC ");
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

				$result["rows"] = $rows;
				//Busco posibles errores:
				if ($result["total"] <= 0) {
					$result["error"] = -1;
					$result["descripcion"] = "No se encuentran detalle del dispositivo: " . $id_dis;
				}	
			}

			

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>