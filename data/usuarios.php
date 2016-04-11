<?php
require_once('../data/conexion.php');
function validaUsuario()
{
	$respuesta		= false;
	$usu 			= GetSQLValueString($_POST["usuario"],"text");
	$cve 			= GetSQLValueString(md5($_POST["cveUsuario"]),"text");
	$tipo			= "0";
	$usuario 		= "";
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select * from lbusuarios where usuario=%s and cveUsuario=%s limit 1",$usu,$cve);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$claveUsuario = $row["claveUsuario"];
		$usuario = $row["usuario"];
		$tipo = $row["tipoUsuario"];
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
						'claveUsuario' 	=> $claveUsuario,
						'usuario' 		=> $usuario,
						'tipo' 			=> $tipo,
						);
	print json_encode($arrayJSON);
}
function claveUsuario1()
{
	$usu 			= GetSQLValueString($_POST["usuario"],"text");
	$respuesta		= false;
	$claveUsuario	= -1;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select * from lbusuarios where usuario=%s limit 1",$usu);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$claveUsuario = $row["claveUsuario"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'claveUsuario' => $claveUsuario);
	print json_encode($arrayJSON); 
} 
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'validaUsuario':
	validaUsuario();
	break;
	case 'claveUsuario1':
	 claveUsuario1();
} 
?>