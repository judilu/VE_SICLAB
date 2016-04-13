<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');
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
//select CARNOM from DCARRE INNER JOIN DCALUM ON DCALUM.CARCVE=DCARRE.CARCVE WHERE DCALUM.ALUCTR='11170876'
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
function consultaMaestroPractica()
{
	$periodo 		= "'2161'";//periodoActual();
	$claveMateria	= GetSQLValueString($_POST["claveMateria"],"text");				
	$conexion		= conectaBDSIE();
	$respuesta 		= false;
	$cveMaestro 	= "";
	$opcionMaestro 	= "";
	$nombreMaestro	= "";
	$con 			= "";
	$cmbMaestrosPrac= array(); 
	$consulta 		= sprintf("select p.PERCVE,p.PERNOM,p.PERAPE 
						from DPERSO p 
						INNER JOIN DGRUPO g on p.PERCVE=g.PERCVE 
						inner JOIN DMATER m on g.MATCVE=m.MATCVE 
						where g.PDOCVE=%s and m.MATCVE=%s GROUP BY p.PERCVE",$periodo,$claveMateria);
	$res 			= mysql_query($consulta);

	while($row = mysql_fetch_array($res))
	{
		$cmbMaestrosPrac[] = $row;
		$respuesta = true;
		$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$opcionMaestro[] 	=$cmbMaestrosPrac[$i]["PERCVE"];
		$nombreMaestro[] 	=$cmbMaestrosPrac[$i]["PERNOM"]." ".$cmbMaestrosPrac[$i]["PERAPE"];
	}
	$arrayJSON = array('respuesta' => $respuesta, 'opcionMaestro' => $opcionMaestro, 
						'nombreMaestro' => $nombreMaestro, 'contador' => $con);
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
} 
?>