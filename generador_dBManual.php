<?php
	
			$conexion=include ("db_connect.php");
			
			$db = new DB_CONNECT();
			
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			
			$dBvalores = array();
			$distanciavalores = array();
			
			$cantidad_valores = 20;
			
			$dBmin=80;$dBmax=100;
			$distanicamin=80;$distanciamax=100;
			
			for($i=0; $i<$cantidad_valores; $i++){
				array_push($dBvalores,rand($dBmin,$dBmax));
				array_push($distanciavalores,rand($distanicamin,$distanciamax));
			}
			
                        
			echo "db: ";
			for($i=0; $i<$cantidad_valores; $i++){
				echo $dBvalores[$i]."<br/>";	
				$hoy = date("Y-m-d H:i:s");
				
				$query = "INSERT INTO detalle_dispo (db, distancia, id_dis, fechahs) VALUES ('$dBvalores[$i]','50','358','$hoy')";
				$rs = mysql_query($query);

				sleep(10);
				
			}
			
			/*
			echo "Distancia: "			
			for($i=0; $i<$cantidad_valores; $i++){
				echo $distanciavalores[$i]."<br/>";				
			}
			*/
		
	?>