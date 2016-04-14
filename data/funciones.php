<?php
require_once('../data/conexion.php');
function salir()
{
	session_start();
	session_destroy();
	$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function periodoActual ()
{
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select PARFOL1 FROM DPARAM WHERE PARCVE='PRDO'");
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["PARFOL1"];
	}
}
//trae el nombre corto de las materias segun una clave
function nomMat ($claves)
{
	$claveMat 	= $claves;
	$materias	= array();
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select MATCVE, MATNCO from DMATER where MATCVE IN (%s)",$claveMat);
	$res 		= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$materias[$row["MATCVE"]] =$row["MATNCO"];
		}
		return $materias;
	}
	else
	{
		return "";
	}
}
function existeCal ($clave)
{
	$claveCal	= $clave;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveCalendarizacion from lbcalendarizaciones where claveCalendarizacion =%d",$claveCal);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		return true;
	}
	return false;
}
function existeSol ($clave)
{
	$claveSol	= $clave;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveSolicitud from lbsolicitudlaboratorios where claveSolicitud =%d",$claveSol);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		return true;
	}
	return false;
}
function claveMaestro($clave)
{
	$claveUsuario 	= $clave;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select PERCVE from lbusuarios where claveUsuario = %d",$claveUsuario);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["PERCVE"]);
	}
	else
	{
		return 0;
	}
}
function grupo($mat,$per,$pdo,$hor)
{
	$matcve  = $mat;
	$clave   = $per;
	$maestro = claveMaestro($clave);
	$periodo = $pdo;
	$n 	 	 = $hor;
	$n2 	 = (string)($n+1);
	$n 		 = (string)($n);
	$hora 	 = $n."00".$n2."00";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select GPOCVE from DGRUPO where MATCVE =%s AND PERCVE=%d AND PDOCVE =%s AND (LUNHRA =%s OR MARHRA =%s OR MIEHRA =%s OR JUEHRA =%s OR VIEHRA =%s)",$matcve,$maestro,$periodo,$hora,$hora,$hora,$hora,$hora);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["GPOCVE"];
	}
	else
	{
		return "";
	}
}
function existeSolLab($dep,$pdo,$fce,$fcs,$hrs,$lab,$pra,$mat,$gpo,$usu)
{
	$d              = $dep;
	$periodo 		= $pdo;
	$fe  			= $fce;
	$fs 			= $fcs;
	$h 				= $hrs;
	$l 				= $lab;
	$p 				= $pra;
	$m 				= $mat;
	$g 				= $gpo;
	$u     			= $usu;	
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveSolicitud from lbsolicitudlaboratorios where claveDependencia =%s and PDOCVE =%s and fechaEnvio =%s and  fechaSolicitud =%s and horaSolicitud =%s and claveLaboratorio =%s and clavePractica =%d and MATCVE =%s and GPOCVE =%s and claveUsuario =%s",$d,$periodo,$fe,$fs,$h,$l,$p,$m,$g,$u);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveSolicitud"];
	}
	else
	{
		return 0;
	}
}
function detalleArt($nu,$so,$art,$num)
{
	$n 			= GetSQLValueString($nu,"int");
	$sol 		= GetSQLValueString($so,"int");;
	$porArt 	= $art;
	$porNum 	= $num;
	$cant 		= 0;
	$respuesta 	= false;
	if($sol != 0)
	{
		for ($i=0; $i <$n; $i++) 
		{ 
			$cant 		= (int)($porNum[$i]);
			$conexion 	= conectaBDSICLAB();
			$consulta 	= sprintf("insert into lbasignaarticulospracticas values('%s',%d,%d,'V')",$porArt[$i],$sol,$cant);
			$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
			{
				$respuesta = true; 
			}
		}	
	}
	return $respuesta;
}
function comboMat ()
{
	session_start();
	$clave  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$maestro 		= claveMaestro($clave);
	$respuesta 		= false;
	$periodo 		= periodoActual();
	$con 			= 0;
	$combomat 		= array();
	$claveMat 		= "";
	$nombreMat		= "";
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select m.MATCVE, m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE =%d and g.PDOCVE =%s and g.GRUBAS = ' ' and g.INSNUM > 0",$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$combomat[] = $row;
			$respuesta = true;
			$con++;
		}
		for ($i=0; $i < $con ; $i++)
		{ 
			$claveMat[] 	=$combomat[$i]["MATCVE"];
			$nombreMat[] 	=$combomat[$i]["MATNCO"];
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'claveMat' => $claveMat, 
						'nombreMat' => $nombreMat, 
						'contador' => $con);
	print json_encode($arrayJSON);

}
function comboMatHr ()
{
	session_start();
	$clave  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$maestro 		= GetSQLValueString((claveMaestro($clave)),"int");
	$periodo 		= periodoActual();
	$respuesta 		= false;
	$comboHr 		= "";
	$comboHrMat     = array();
	$cont 			= 0;
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select LUNHRA,MARHRA,MIEHRA,JUEHRA,VIEHRA from DGRUPO where MATCVE =%s and PERCVE=%d and PDOCVE =%s",$materia,$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{		
		$comboHr 	= $row[0].",".$row[1].",".$row[2].",".$row[3].",".$row[4];
		$comboHrMat = array_unique((explode(",",$comboHr)));
		$respuesta 	= true;
		$cont 		= count($comboHrMat);
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'comboHr' => $comboHrMat,
						 'cont' => $cont); 
	print json_encode($arrayJSON);

}
function comboPract()
{
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$periodo 		= GetSQLValueString(periodoActual(),"text");
	$respuesta 		= false;
	$comboPrac 		= array();
	$comboCvePrac 	= "";
	$comboTitPrac 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select p.clavePractica, p.tituloPractica from lbpracticas p inner join lbasignapracticas ap on p.clavePractica = ap.clavePractica where p.estatus = 'V' and ap.MATCVE =%s and ap.PDOCVE =%s",$materia,$periodo);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboPrac[] = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCvePrac[] 	=$comboPrac[$i]["clavePractica"];
		$comboTitPrac[] 	=$comboPrac[$i]["tituloPractica"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCvePrac' => $comboCvePrac, 
						'comboTitPrac' => $comboTitPrac, 
						'con' => $con);
	print json_encode($arrayJSON);	
}
function comboLab()
{
	$practica 		= GetSQLValueString($_POST["practica"],"int");
	$respuesta 		= false;
	$comboLab 		= array();
	$comboCveLab 	= "";
	$comboNomLab 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select ap.claveLaboratorio, l.nombreLaboratorio from lbasignapracticas ap inner join lbpracticas p on ap.clavePractica = p.clavePractica inner join lblaboratorios l on ap.claveLaboratorio = l.claveLaboratorio where (l.usoAsignado = '1' or '3') and p.estatus = 'V' and ap.clavePractica=%d",$practica);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboLab[]  = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCveLab[] 	=$comboLab[$i]["claveLaboratorio"];
		$comboNomLab[] 	=$comboLab[$i]["nombreLaboratorio"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCveLab' => $comboCveLab, 
						'comboNomLab' => $comboNomLab, 
						'con' => $con);
	print json_encode($arrayJSON);
}
function comboHoraPrac()
{
	$laboratorio 	= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$comboHrAp 		= "";
	$comboHrC 		= "";
	$capacidad 		= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select horaApertura, horaCierre, capacidad from lblaboratorios where claveLaboratorio =%s",$laboratorio);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
			$comboHrAp  = $row["horaApertura"];
			$comboHrC 	= $row["horaCierre"];
			$capacidad  = (int)($row["capacidad"]);
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'horaApertura' => $comboHrAp, 
						'horaCierre' => $comboHrC,
						'capacidad' => $capacidad);
	print json_encode($arrayJSON);
}
function comboEleArt()
{
	$laboratorio 		= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$comboEleArt 	= array();
	$comboCveArt 	= "";
	$comboNomArt 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select c.claveArticulo, c.nombreArticulo from lbarticuloscat c inner join lbarticulos a on a.claveArticulo = c.claveArticulo inner join lbasignaarticulos aa on aa.indentificadorArticulo = a.identificadorArticulo where aa.claveLaboratorio =%s and a.estatus = 'V'",$laboratorio);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboEleArt[]  = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCveArt[] 	=$comboEleArt[$i]["claveArticulo"];
		$comboNomArt[] 	=$comboEleArt[$i]["nombreArticulo"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCveArt' => $comboCveArt, 
						'comboNomArt' => $comboNomArt, 
						'con' => $con);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc)
{
	case 'salir1':
		salir();
	break;
	case 'comboMat1':
		comboMat();
	break;
	case 'comboMatHr1':
		comboMatHr();
		break;
	case 'comboPract1':
		comboPract();
		break;
	case 'comboLab1':
		comboLab();
		break;
	case 'comboHoraPrac1':
		comboHoraPrac();
		break;
	case 'comboEleArt1':
		comboEleArt();
		break;
}	 
?>