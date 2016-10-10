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

				$FROM = "FROM inspector as INS INNER JOIN acta as ACT ON INS.id=ACT.id_ins INNER JOIN alarma as ALA ON ALA.id=ACT.id_ala INNER JOIN tipoalarma as TA ON TA.id=ALA.id_tipAla  INNER JOIN sucursal as SUC ON ACT.id_suc=SUC.id INNER JOIN empresa as EMP on EMP.cuit=SUC.cuit_emp INNER JOIN direccion as DIR ON DIR.id=SUC.id_dir "					
					. "WHERE ACT.id_ins='$id_ins'";

				$query = "SELECT  ACT.observacion, ACT.fechahs as fecha, ACT.fechahs as hs, TA.descripcion as nom_TipAla, CONCAT_ws(' ',DIR.calle,DIR.altura, DIR.piso, DIR.depto) AS direccion,  SUC.nombre as nom_suc, EMP.razonsocial as emp_razsoc " . $FROM;

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
				$row->fecha=date_format($date, 'd/m/Y');
					$hs=$fechahs[1];
				$row->hs=$hs;
				array_push($rows, $row);
			}
			$result["rows"] = $rows;
			//Busco posibles errores:
			if ($result["total"] <= 0) {
				$result["error"] = -1;
				$result["descripcion"] = "El inspector no ha generado actas de infraccion";
			}

		} catch (exception $e) {
			$result["error"] = -1;
			$result["descripcion"] = "Error al ejecutar SQL";

		}

	echo json_encode($result);
	?>