<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
  $theValue =  htmlentities($theValue); 
  //Lo contrario de htmlentities es html_entity_decode(string)
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

function conectaBDSIE()
{
	//Servidor, Usuario, Contraseña
	$conexion = mysql_connect("itculiacan.edu.mx", "sieapibduser", "B5fa4x_7*.*") ;
/*  if(!$conexion){
    die("error " . mysql_error());
  }*/
	//Seleccionamos la BD
mysql_select_db("sieapibd");
	return $conexion;
}
function conectaBDSICLAB()
{
	//Servidor, Usuario, Contraseña
	$conexion = mysql_connect("localhost","siclabuser","Ef22Ap17Jg12*.*");
	//Seleccionamos la BD
mysql_select_db("siclabbd");
mysql_query("SET NAMES UTF8");
	return $conexion;
}
?>