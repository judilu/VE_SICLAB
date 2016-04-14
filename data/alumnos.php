<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');
function usuario ()
{
	session_start();
	$_SESSION['nombre'] = GetSQLValueString($_POST['clave1'],"int");
}
function claveUsuario($clavePersona)
{
	$clavePer 		= $clavePersona;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveUsuario from lbusuarios where PERCVE = %d",$clavePer);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["claveUsuario"]);
	}
	else
	{
		return 0;
	}
}
function consultaAlumno(){
	$respuesta		= false;
	$nControl		= GetSQLValueString($_POST["nControl"],"text");
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
	$claveMaestro	= GetSQLValueString($_POST["claveMaestro"],"int");	
	$claveUsu 		= claveUsuario($claveMaestro);			
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
								WHERE sl.PDOCVE =%s and sl.claveUsuario =%s",$periodo,$claveUsu);
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
	$claveC 		= GetSQLValueString($_POST["clavePrac"],"int");				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$comboHorario	= array();
	$clavePractica 	= "";
	$horaPractica	= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select horaAsignada,claveCalendarizacion 
								from lbcalendarizaciones  
								WHERE PDOCVE =%s and claveCalendarizacion=%s",$periodo,$claveC);
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
function guardaEntrada()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$periodo 		= periodoActual();
		$claveCal		= GetSQLValueString($_POST["claveCal"],"text");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$hora 			= GetSQLValueString($_POST["hora"],"text");
		$numControl 	= GetSQLValueString($_POST["nControl"],"text");
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("insert into lbentradasalumnos values(%s,%s,%s,%s,%s)",$periodo,$numControl,$fecha,$hora,$claveCal);
		$res 	 	=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
			$respuesta = true; 
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
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
	case 'guardaEntrada':
		guardaEntrada();
	break;
	case 'consultaNomAlumno':
		consultaAlumno();
	break;
} 
?>