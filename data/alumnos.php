<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');
function usuario ()
{
	session_start();
	$_SESSION['nombre'] = GetSQLValueString($_POST['clave1'],"int");
}
function consultaAlumno(){
	$respuesta		= false;
	$nControl			= GetSQLValueString($_POST["nControl"],"text");
	$ALUAPP 		= "";
	$ALUAPM			="";
	$ALUNOM			="";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select ALUAPP, ALUAPM, ALUNOM from DALUMN where ALUCTR=%s limit 1",$nControl);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$ALUAPP=$row['ALUAPP'];
		$ALUAPM=$row['ALUAPM'];
		$ALUNOM=$row['ALUNOM'];
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
						'ALUAPP' 		=> $ALUAPP,
						'ALUAPM'		=> $ALUAPM,
						'ALUNOM'		=> $ALUNOM
						);
	print json_encode($arrayJSON);
}

function consultaCarrera(){
	$respuesta		= false;
	$nControl		= GetSQLValueString($_POST["nControl"],"text");
	$CARNOM 		= "";
	$CALNPE			="";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select DCARRE.CARNOM, DCALUM.CALNPE from DCARRE INNER JOIN DCALUM ON DCALUM.CARCVE=DCARRE.CARCVE WHERE DCALUM.ALUCTR=%s limit 1",$nControl);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$CARNOM=$row['CARNOM'];
		$CALNPE=$row['CALNPE'];
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
						'CARNOM' 		=> $CARNOM,
						'CALNPE'		=> $CALNPE
						);
	print json_encode($arrayJSON);
}
function consultaMateriasAlumno()
{
		$numControl 	= GetSQLValueString($_POST["numeroControl"],"text");
		$resp 	 		= false;
		$periodo 		= periodoActual();
		$contador		= 0;
		$comboMat 		= array();
		$claveMat 		= "";
		$nombreMat		= "";
		$conexion		= conectaBDSIE();
		$consulta 		= sprintf("select m.MATCVE, m.MATNCO 
						from DMATER m 
						inner join DGRUPO g on m.MATCVE=g.MATCVE 
						INNER JOIN DLISTA l ON g.MATCVE=l.MATCVE 
						where g.PDOCVE=%s and l.ALUCTR=%s 
							GROUP BY m.MATCVE",$periodo,$numControl);
	$res 			= mysql_query($consulta);
		if($res)
		{
			while($row = mysql_fetch_array($res))
			{
				$combomat[] 	= $row;
				$resp 		 	= true;
				$contador++;
			}
			for ($i=0; $i < $contador ; $i++)
			{ 
				$claveMat[] 	=$combomat[$i]["MATCVE"];
				$nombreMat[] 	=$combomat[$i]["MATNCO"];
			}
		}
	$arrayJSON = array('respuesta' => $resp,'claveMateria' => $claveMat,'nombreMateria' => $nombreMat, 
						'contador' => $contador);
	print json_encode($arrayJSON);
}
function consultaMaestroPractica()
{
	$claveMateria	= GetSQLValueString($_POST["claveMateria"],"text");				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$comboMaestros	= array();
	$claveMaestro 	= "";
	$nombreMaestro	= "";
	$conexion		= conectaBDSIE();
	$consulta 		= sprintf("select p.PERCVE,p.PERNOM,p.PERAPE 
						from DPERSO p 
						INNER JOIN DGRUPO g on p.PERCVE=g.PERCVE 
						inner JOIN DMATER m on g.MATCVE=m.MATCVE 
						where g.PDOCVE=%s and m.MATCVE=%s GROUP BY p.PERCVE",$periodo,$claveMateria);
	$res 			= mysql_query($consulta);
	if($res)
			{
				while($row = mysql_fetch_array($res))
				{
					$comboMaestros[] 	= $row;
					$resp 		 	= true;
					$contador++;
				}
				for ($i=0; $i < $contador ; $i++)
				{ 
					$claveMaestro[] 	=$comboMaestros[$i]["PERCVE"];
					$nombreMaestro[] 	=$comboMaestros[$i]["PERNOM"].$comboMaestros[$i]["PERAPE"];
				}
			}
	$arrayJSON = array('respuesta' => $resp,'claveMaestro' => $claveMaestro,'nombreMaestro' => $nombreMaestro, 
						'contador' => $contador);
	print json_encode($arrayJSON);
}
function consultaPractica()
{
	$claveMaestro	= GetSQLValueString($_POST["claveMaestro"],"text");				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$comboPracticas	= array();
	$clavePractica 	= "";
	$nombrePractica	= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select p.tituloPractica,c.claveCalendarizacion 
							from lbpracticas p  
							INNER JOIN lbsolicitudlaboratorios sl on p.clavePractica=sl.clavePractica 
							INNER JOIN lbcalendarizaciones c on sl.claveSolicitud=c.claveSolicitud 
								WHERE sl.PDOCVE =%s and sl.claveUsuario =%s",$periodo,$claveMaestro);
	$res 			= mysql_query($consulta);
	if($res)
			{
				while($row = mysql_fetch_array($res))
				{
					$comboPracticas[] 	= $row;
					$resp 		 		= true;
					$contador++;
				}
				for ($i=0; $i < $contador ; $i++)
				{ 
					$clavePractica[] 	=$comboPracticas[$i]["claveCalendarizacion"];
					$nombrePractica[] 	=$comboPracticas[$i]["tituloPractica"];
				}
			}
	$arrayJSON = array('respuesta' => $resp,'clavePractica' => $clavePractica,'nombrePractica' => $nombrePractica, 
						'contador' => $contador);
	print json_encode($arrayJSON);
}
function consultaHoraPractica()
{
	$clavePractica	= GetSQLValueString($_POST["clavePractica"],"text");				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$comboHorario	= array();
	$clavePractica 	= "";
	$horaPractica	= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select horaAsignada,claveCalendarizacion 
								from lbcalendarizaciones  
								WHERE sl.PDOCVE =%s and c.claveCalendarizacion=%s",$periodo,$clavePractica);
	$res 			= mysql_query($consulta);
	if($res)
			{
				while($row = mysql_fetch_array($res))
				{
					$comboHorario[] 	= $row;
					$resp 		 		= true;
					$contador++;
				}
				for ($i=0; $i < $contador ; $i++)
				{ 
					$clavePractica[] 	=$comboHorario[$i]["claveCalendarizacion"];
					$horaPractica[] 	=$comboHorario[$i]["horaAsignada"];
				}
			}
	$arrayJSON = array('respuesta' => $resp,'clavePractica' => $clavePractica,'horaPractica' => $horaPractica, 
						'contador' => $contador);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'consultaAlumno':
		consultaAlumno();
	break;
	case 'consultaCarrera':
		consultaCarrera();
	break;
	case 'consultaMaestro':
		consultaMaestroPractica();
	break;
	case 'consultaMatAlumno';
		consultaMateriasAlumno();
	break;
	case 'consultaPracticaNombre':
		consultaPractica();
	break;
	case 'consultaHoraPractica':
		consultaHoraPractica();
	break;
} 
?>