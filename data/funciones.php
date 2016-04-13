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
//trae todas las materias de ese maestro
function materias ($clave)
{	
	$maestro		= $clave;
	$periodo 		= periodoActual();
	$materias 		= array();
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select m.MATCVE, m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE =%d and g.PDOCVE =%s and g.GRUBAS = ' ' and g.INSNUM > 0",$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			//$materias[$row["MATCVE"]] =$row["MATNCO"];
			$materias = $row;
		}
		return $materias;
	}
	else
	{
		return " ";
	}
}
function nomPractica ($clave)
{
	$clavePractica 	= $clave;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select tituloPractica from lbpracticas where clavePractica = %d",$clavePractica);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		return  $row["tituloPractica"];

	}
}
function nomLab ($clave)
{
	$claveLab 	= $clave;
	$conexion	= conectaBDSICLAB();
	$consulta	= sprintf("select nombreLaboratorio from lblaboratorios where claveLaboratorio =%s",$claveLab);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		return  $row["nombreLaboratorio"];
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
//Menú principal
$opc = $_POST["opc"];
switch ($opc)
{
	case 'salir1':
	salir();
	break;
}	 
?>