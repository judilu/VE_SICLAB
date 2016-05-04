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
function consultaLab($claveSol)
{
	$sol 		= $claveSol;
	$lab 		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveLaboratorio from lbsolicitudlaboratorios WHERE claveSolicitud =%d",$sol);
	$res		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$lab = $row["claveLaboratorio"];
	}
	return $lab;	
}
function consultaAlumno()
{
	$respuesta		= false;
	$nControl		= GetSQLValueString($_POST["nControl"],"text");
	$ALUAPP 		= "";
	$ALUAPM			= "";
	$ALUNOM			= "";
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
function consultaExterno()
{
	$respuesta		= false;
	$nControl		= GetSQLValueString($_POST["nControl"],"int");
	$dependencia 	= "";
	$encargado 		= "";
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select nombreDependencia,nombreEncargado 
		from lbdependencias 
		where claveDependencia=%d",$nControl);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$dependencia=$row['nombreDependencia'];
		$encargado 	=$row['nombreEncargado'];
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
		'dependencia' 	=> $dependencia,
		'encargado' 	=> $encargado);
	print json_encode($arrayJSON);
}
function consultaCarrera()
{
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
	$periodo 		= '2161';
	$contador		= 0;
	$comboMat 		= array();
	$claveMat 		= "";
	$nombreMat		= "";
	$conexion		= conectaBDSIE();
	$consulta 		= sprintf("select l.MATCVE,m.MATNCO 
		FROM DLISTA l 
		inner join DMATER m on l.MATCVE=m.MATCVE 
		WHERE l.PDOCVE=%s AND l.ALUCTR=%s",$periodo,$numControl);
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
	$consulta 		= sprintf("select G.MATCVE,M.MATNCO,G.PERCVE, P.PERNOM,P.PERAPE
		from DGRUPO G 
		INNER JOIN DMATER M ON G.MATCVE=M.MATCVE 
		INNER JOIN DPERSO P ON G.PERCVE=P.PERCVE
		WHERE G.PDOCVE=%s AND G.MATCVE=%s GROUP BY G.PERCVE",$periodo,$claveMateria);	
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
			$nombreMaestro[] 	=$comboMaestros[$i]["PERNOM"]." ".$comboMaestros[$i]["PERAPE"];
		}
	}
	$arrayJSON = array('respuesta' => $resp,'claveMaestro' => $claveMaestro,'nombreMaestro' => $nombreMaestro, 
		'contador' => $contador);
	print json_encode($arrayJSON);
}
function consultaPractica()
{
	$claveCal		= GetSQLValueString($_POST["horaPrac"],"int");				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$nombrePractica	= "";
	$clavePractica	= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select p.tituloPractica,c.claveCalendarizacion 
		from lbpracticas p  
		INNER JOIN lbsolicitudlaboratorios sl on p.clavePractica=sl.clavePractica 
		INNER JOIN lbcalendarizaciones c on sl.claveSolicitud=c.claveSolicitud 
		WHERE sl.PDOCVE =%s and c.claveCalendarizacion =%s",$periodo,$claveCal);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$nombrePractica 	= $row["tituloPractica"];
		$clavePractica 		= $row["claveCalendarizacion"];
		$resp 		 		= true;
		$contador++;
	}
	$arrayJSON = array('respuesta' => $resp,
		'nombrePractica' => $nombrePractica,
		'clavePractica' => $clavePractica, 
		'contador' => $contador);
	print json_encode($arrayJSON);
}
function consultaHoraPractica()
{
	$claveMaestro 	= GetSQLValueString($_POST["claveMaestro"],"int");
	$fechaActual 	= GetSQLValueString($_POST["fecha"],"text");
	$claveUsu 		= claveUsuario($claveMaestro);				
	$resp 	 		= false;
	$periodo 		= periodoActual();
	$contador		= 0;
	$clavePractica 	= "";
	$horaPractica	= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select c.horaAsignada,c.claveCalendarizacion 
		from lbsolicitudlaboratorios sl 
		inner join lbcalendarizaciones c on sl.claveSolicitud=c.claveSolicitud
		where sl.PDOCVE=%s 
		and sl.claveUsuario=%s 
		and c.fechaAsignada=%s",$periodo,$claveUsu,$fechaActual);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$clavePractica 	=$row["claveCalendarizacion"];
		$horaPractica	=$row["horaAsignada"];
		$resp 		 	= true;
		$contador ++;
	}
	$arrayJSON = array('respuesta' => $resp,
		'clavePractica' => $clavePractica,
		'horaPractica' => $horaPractica, 
		'contador' => $contador);
	print json_encode($arrayJSON);
}
function guardaEntrada()
{
	$respuesta 		= false;
	session_start();
	
	$periodo 		= periodoActual();
	$claveCal		= GetSQLValueString($_POST["claveCal"],"text");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$hora 			= GetSQLValueString($_POST["hora"],"text");
	$numControl 	= GetSQLValueString($_POST["nControl"],"int");
	$conexion 		= conectaBDSICLAB();
	$query  		= sprintf("insert into lbentradasalumnos values(%s,%s,%s,%s,%s)",$periodo,$numControl,$fecha,$hora,$claveCal);
	$res 	 	=  mysql_query($query);
	if(mysql_affected_rows()>0)
		$respuesta = true; 
	
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function insertaPedido($fecha,$hora,$clave,$numCtrl)
{
	$responsable	= "6";
	$cvePrestamo 	= "0";
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSICLAB();
	$query  		= sprintf("insert into lbprestamos values(%s,%d,%d,%s,%s,%s,%s)",$periodo,$cvePrestamo,$responsable,$numCtrl,$fecha,$hora,$clave);
	$res 	 		=  mysql_query($query);
	if(mysql_affected_rows()>0)
	{
		return true; 
	}
	return false;
}
function consultaMaterialPractica()
{
	$respuesta 		= false;
	$periodo 		= periodoActual();
	$claveCal		= GetSQLValueString($_POST["clave"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$hora 			= GetSQLValueString($_POST["hora"],"text");
	$numeroCtrl		= GetSQLValueString($_POST["nC"],"text");
	if(insertaPedido($fecha,$hora,$claveCal,$numeroCtrl))
	{
		$claveSol 		= consultaSolicitud($claveCal,$fecha);
		$con 			= 0;
		$rows			= array();
		$renglones		= "";
		$materiales 	= "";
		$cantidad 		= "";
		$nombre 		= "";
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select ap.claveArticulo,ac.nombreArticulo,ap.cantidad 
			from lbasignaarticulospracticas ap 
			INNER JOIN lbarticuloscat ac on ap.claveArticulo=ac.claveArticulo 
			WHERE ap.estatus = 'V' and ap.claveSolicitud=%s",$claveSol);
		$res 			= mysql_query($consulta);
		while($row = mysql_fetch_array($res))
		{
			$materiales .=/*"'".*/($row["claveArticulo"]).",";
			$cantidad 	.=/*"'".*/($row["cantidad"]).",";
			$nombre 	.=/*"'".*/($row["nombreArticulo"]).",";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$materiales = (rtrim($materiales,","));
		$cantidad = (rtrim($cantidad,","));
		$nombre = (rtrim($nombre,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tr>";
			$renglones .= "<td class='col s6'>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td class='col s3'>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td class='col s3'><a name = '".$rows[$c]["claveArticulo"]."' class='btn-floating btn-large waves-effect red darken-1' id='btnEliminarArtAlu'><i class='material-icons'>delete</i></a></td>";
			$renglones .= "</tr>";
			$respuesta = true;
		}
		$arrayJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones, 
			'contador' => $con,
			'materiales' => $materiales,
			'cantidad' => $cantidad,
			'nombre' => $nombre);
		print json_encode($arrayJSON);
	}
}
/*function agregarArtAlumno()
{
	$cveArt 	= GetSQLValueString($_POST['artCve'],"text");
	$num 	 	= GetSQLValueString($_POST['numArt'],"int");
	$nomArt 	= $_POST['artNom'];
	$respuesta	= true;
	$renglones	= "";
	$renglones .= "<tr id=".$cveArt.">";
	$renglones .= "<td class='col s6'>".$nomArt."</td>";
	$renglones .= "<td class='col s3'>".$num."</td>";
	$renglones .= "<td class='col s3'><a name =".$cveArt."class='btn-floating btn-large waves-effect waves-light red darken-1' id='btnEliminarArtAlu'><i class='material-icons'>delete</i></a></td>";
	$renglones .= "</tr>";
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}*/
function materialesDisponibles()
{
	$claveSol 		= GetSQLValueString($_POST['claveSol'],"int");
	$resp 	 		= false;
	$contador		= 0;
	$laboratorio 	= GetSQLValueString(consultaLab($claveSol),"text");
	$comboArticulos	= array();
	$claveArt 		= "";
	$nombreArt		= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select DISTINCT (c.nombreArticulo), c.claveArticulo from lbarticuloscat c inner join lbarticulos a on a.claveArticulo = c.claveArticulo inner join lbasignaarticulos aa on aa.indentificadorArticulo = a.identificadorArticulo where aa.claveLaboratorio =%s and a.estatus = 'V'",$laboratorio);	
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$comboArticulos[] 	= $row;
			$resp 		 		= true;
			$contador++;
		}
		for ($i=0; $i < $contador ; $i++)
		{ 
			$claveArt[] 	=$comboArticulos[$i]["claveArticulo"];
			$nombreArt[] 	=$comboArticulos[$i]["nombreArticulo"];
		}
	}
	$arrayJSON = array('respuesta' => $resp,
						'claveArticulo' => $claveArt,
						'nombreArticulo' => $nombreArt, 
				'contador' => $contador);
	print json_encode($arrayJSON);

}
/*function comboEleArtAlu()
{
	$laboratorio 		= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$comboEleArt 	= array();
	$comboCveArt 	= "";
	$comboNomArt 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select DISTINCT (c.nombreArticulo), c.claveArticulo from lbarticuloscat c inner join lbarticulos a on a.claveArticulo = c.claveArticulo inner join lbasignaarticulos aa on aa.indentificadorArticulo = a.identificadorArticulo where aa.claveLaboratorio =%s and a.estatus = 'V'",$laboratorio);
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
}*/
function consultaClavePrestamo($clave,$numCtrl)
{
	$responsable	= "6";
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSICLAB();
	$query  		= sprintf("select clavePrestamo 
		from lbprestamos 
		where claveCalendarizacion=%s and ALUCTR=%s and PDOCVE=%s ",$clave,$numCtrl,$periodo);
	$res 	 		=  mysql_query($query);
	if($row = mysql_fetch_array($res))
	{
		return $row["clavePrestamo"];
	}
	return "";
}
function guardaSolicitudAlumno()
{
	$respuesta 		= false;
	
	$periodo 		= periodoActual();
	$claveCal		= GetSQLValueString($_POST["claveCal"],"int");
	$numeroCtrl		= GetSQLValueString($_POST["numC"],"text");
	$clavePrestamo 	= consultaClavePrestamo($claveCal,$numeroCtrl);
	$listaArt		= $_POST['listaArt'];
	$cantArt		= $_POST['cantArt'];
	$arrayArt 		= explode(',',$listaArt); 
	$arrayCant 		= explode(',',$cantArt); 
	$cantidad 		= count($arrayArt);
	for ($i=0; $i < $cantidad ; $i++) { 
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("insert into lbsolicitudarticulos values(%s,%s,%s,%s,%s)",'""',
			$arrayArt[$i],$clavePrestamo,$arrayCant[$i],'"S"');
		$res 		= mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);

}
function construirTablaArt()
{
	$cve 		= $_POST['articulosSolicitados'];
	$nom 		= $_POST['nombreArticulos'];
	$num 		= $_POST['numeroArticulos'];
	$cveArt 	= explode(",",$cve);
	$nomArt 	= explode(",",$nom);
	$numArt 	= explode(",",$num);
	$n 			= count($cveArt);
	$respuesta 	= false;
	$renglones 	= "";
	for ($i=0; $i < $n ; $i++) 
	{ 
		if ($cveArt[$i]!= "") 
		{
			$respuesta	= true;
			$renglones .= "<tr>";
			$renglones .= "<td class='col s6'>".$nomArt[$i]."</td>";
			$renglones .= "<td class='col s3'>".$numArt[$i]."</td>";
			$renglones .= "<td class='col s3'><a name ='".$cveArt[$i]."' class='btn-floating btn-large waves-effect waves-light red darken-1' id='btnEliminarArtAlu'><i class='material-icons'>delete</i></a></td>";
			$renglones .= "</tr>";
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);	
}
function consultaCalExt()
{
	$periodo 		= '"2161"';//periodoActual();
	$claveDep		= GetSQLValueString($_POST["ncExterno"],"text");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$claveCal 		= 0;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select claveSolicitud from lbsolicitudlaboratorios
							where PDOCVE=%s and claveDependencia=%s",$periodo,$claveDep);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
		{
			$claveCal 	= calendarizacionExt(($row["claveSolicitud"]),$fecha);
			$respuesta 	= true;
		}
	else
		{
			$respuesta = false;
		}
	$arrayJSON = array('respuesta' 			=> $respuesta,
						'calendarizacion' 	=> $claveCal);
	print json_encode($arrayJSON);
}
function calendarizacionExt($cveSol,$fecha)
{
	$clave 			= $cveSol;
	$fe 			= $fecha;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveCalendarizacion from lbcalendarizaciones 
								where claveSolicitud=%d and fechaAsignada=%s",$clave,$fe);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["claveCalendarizacion"]);
	}
	else
	{
		return 0;
	}
}
function consultaMaterialExterno()
{
	$respuesta 		= false;
	$claveDep		= GetSQLValueString($_POST["nC"],"text");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$hora 			= GetSQLValueString($_POST["hora"],"text");
	$claveCal 		= GetSQLValueString($_POST["calendarizacion"],"int");
	$claveSol 		= consultaSolicitud($claveCal,$fecha);
	if(insertaPedido($fecha,$hora,$claveCal,$claveDep))
	{
		$con 			= 0;
		$rows			= array();
		$renglones		= "";
		$materiales 	= "";
		$cantidad 		= "";
		$nombre 		= "";
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select ap.claveArticulo,ac.nombreArticulo,ap.cantidad 
				from lbasignaarticulospracticas ap 
				INNER JOIN lbarticuloscat ac on ap.claveArticulo=ac.claveArticulo 
				WHERE ap.estatus = 'V' and ap.claveSolicitud=%s",$claveSol);
			$res 			= mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{
				$materiales .=($row["claveArticulo"]).",";
				$cantidad 	.=($row["cantidad"])."',";
				$nombre 	.=($row["nombreArticulo"]).",";
				$rows[]=$row;
				$respuesta = true;
				$con++;
			}
			$materiales = (rtrim($materiales,","));
			$cantidad = (rtrim($cantidad,","));
			$nombre = (rtrim($nombre,","));
			for($c= 0; $c< $con; $c++)
			{
				$renglones .= "<tr>";
				$renglones .= "<td class='col s6'>".$rows[$c]["nombreArticulo"]."</td>";
				$renglones .= "<td class='col s3'>".$rows[$c]["cantidad"]."</td>";
				$renglones .= "<td class='col s3'><a name = '".$rows[$c]["claveArticulo"]."' class='btn-floating btn-large waves-effect red darken-1' id='btnEliminarArtAlu'><i class='material-icons'>delete</i></a></td>";
				$renglones .= "</tr>";
				$respuesta = true;
			}
		}
		$arrayJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones, 
			'contador' => $con,
			'materiales' => $materiales,
			'cantidad' => $cantidad,
			'nombre' => $nombre);
		print json_encode($arrayJSON);
}
function consultaSolicitud($claveCal,$fecha)
{
	$calendarizacion 	= $claveCal;
	$fechaAsignada 		= $fecha;
	$conexion 			= conectaBDSICLAB();
	$consulta			= sprintf("select claveSolicitud 
									from lbcalendarizaciones
									WHERE claveCalendarizacion=%s and fechaAsignada=%s",$calendarizacion,$fechaAsignada);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["claveSolicitud"]);
	}
	else
	{
		return 0;
	}

}
function guardaEntradaExt()
{
	$periodo 		= '"2161"';//periodoActual();
	$claveCal		= GetSQLValueString($_POST["calendarizacion"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$hora 			= GetSQLValueString($_POST["hora"],"text");
	$numControl 	= GetSQLValueString($_POST["nControl"],"int");
	$conexion 		= conectaBDSICLAB();
	$query  		= sprintf("insert into lbentradasexternos values(%s,%s,%s,%s,%s)",$periodo,$numControl,$fecha,$hora,$claveCal);
	$res 	 	=  mysql_query($query);
	if(mysql_affected_rows()>0)
		$respuesta = true; 
	
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function guardaEntradaExtMat($claveCal,$numeroDep,$fecha,$hora)
{
	$periodo 		= '"2161"';//periodoActual();
	$clave 			= $claveCal;
	$numDep 		= $numeroDep;
	$fechaE 		= $fecha;
	$horaE 			= $hora;
	$conexion 		= conectaBDSICLAB();
	$query  		= sprintf("insert into lbentradasexternos values(%s,%s,%s,%s,%d)",$periodo,$fechaE,$horaE,$numDep,$clave);
	$res 	 	=  mysql_query($query);
	if(mysql_affected_rows()>0)
	{
		return true; 
	}
	return false;
}
function guardaSolicitudExterno()
{
	$respuesta 		= false;
	$periodo 		= '"2161"';//periodoActual();
	$claveCal		= GetSQLValueString($_POST["claveCal"],"int");
	$numeroDep		= GetSQLValueString($_POST["numC"],"text");
	$fecha			= GetSQLValueString($_POST["fecha"],"text");
	$hora			= GetSQLValueString($_POST["hora"],"text");
	$respuesta2 	= guardaEntradaExtMat($claveCal,$numeroDep,$fecha,$hora);
	$clavePrestamo 	= consultaClavePrestamo($claveCal,$numeroDep);
	$listaArt		= $_POST['listaArt'];
	$cantArt		= $_POST['cantArt'];
	$arrayArt 		= explode(',',$listaArt); 
	$arrayCant 		= explode(',',$cantArt); 
	$cantidad 		= count($arrayArt);
	for ($i=0; $i < $cantidad ; $i++) { 
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("insert into lbsolicitudarticulos values(%s,%s,%s,%s,%s)",'""',
			$arrayArt[$i],$clavePrestamo,$arrayCant[$i],'"S"');
		$res 		= mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'respuesta2' => $respuesta2);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'consultaAlumno':
	consultaAlumno();
	break;
	case 'consultaExterno':
	consultaExterno();
	break;
	case 'consultaMaterialExterno':
	consultaMaterialExterno();
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
	case 'guardaEntrada1':
	guardaEntrada();
	break;
	case 'guardaEntradaExt':
		guardaEntradaExt();
	break;
	case 'consultaNomAlumno':
	consultaAlumno();
	break;
	case 'consultaMaterialPractica1':
	consultaMaterialPractica();
	break;
	// case 'agregarArtAlu1':
	// agregarArtAlumno();
	// break;
	case 'materialesDisponibles1':
	materialesDisponibles();
	break;
	case 'guardaSolicitudAlu':
	guardaSolicitudAlumno();
	break;
	case 'construirTablaArt1':
	construirTablaArt();
	break;
	case 'consultaCalExt':
	consultaCalExt();
	break;
	case 'guardaSolicitudExterno':
		guardaSolicitudExterno();
	break;
} 
?>