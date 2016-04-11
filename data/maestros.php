<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');
function usuario ()
{
	session_start();
	$_SESSION['nombre'] = GetSQLValueString($_POST['clave1'],"int");
}
function solicitudesAceptadas ()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$periodo	= periodoActual();
		$maestro	= $_SESSION['nombre'];
		$mat 		= "";
		$materias 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select c.claveCalendarizacion, s.MATCVE, p.tituloPractica, l.nombreLaboratorio, c.fechaAsignada, c.horaAsignada from lbcalendarizaciones c INNER JOIN lbsolicitudlaboratorios s ON s.claveSolicitud = c.claveSolicitud INNER JOIN lblaboratorios l ON l.claveLaboratorio = s.claveLaboratorio INNER JOIN lbpracticas p on p.clavePractica = s.clavePractica WHERE c.PDOCVE =%s AND s.claveUsuario =%d AND c.estatus = 'NR'",$periodo,$maestro);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$mat 	.= "'".($row["MATCVE"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$mat = (rtrim($mat,","));
		$materias = nomMat($mat);
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$materias[$rows[$c]["MATCVE"]]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreLaboratorio"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveCalendarizacion"]."' class='btn-floating btn-large waves-effect waves-light green darken-2'><i class='material-icons'>thumb_up</i></a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function liberarPractica ()
{
	$claveCal 		= GetSQLValueString($_POST["clave"],"int");
	if(existeCal($claveCal))
	{
		$respuesta 		= false;
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("update lbcalendarizaciones set estatus = 'R' where claveCalendarizacion =%d",$claveCal);
		$res 	 		=  mysql_query($consulta);
		if($res)
		{	
			$respuesta = true;
		}
		$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
	}
}
function solicitudesPendientes ()
{	
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$periodo	= periodoActual();
		$maestro	= $_SESSION['nombre'];
		$mat   		= "";
		$materias 	= "";
		$con 		= 0;
		$rows 		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select s. claveSolicitud, MATCVE, p.tituloPractica, l.nombreLaboratorio, s.fechaSolicitud, s.horaSolicitud from lbsolicitudlaboratorios s inner join lbpracticas p on s.clavePractica = p.clavePractica inner join lblaboratorios l on s.claveLaboratorio = l.claveLaboratorio left join lbcalendarizaciones c ON c.claveSolicitud = s.claveSolicitud where s.PDOCVE =%s and s.claveUsuario =%s and s.estatus = 'V' and c.claveSolicitud is NULL",$periodo,$maestro);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='acciones'>Acciones</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$mat 		.= "'".($row["MATCVE"])."',";
			$rows[] 	= $row;
			$respuesta 	= true;
			$con++;
		}
		$mat = (rtrim($mat,","));
		$materias = nomMat($mat);
		for($c=0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$materias[$rows[$c]["MATCVE"]]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreLaboratorio"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td><input type='hidden'/><a name = '".$rows[$c]["claveSolicitud"]."' class='btnEditarSolicitudLab btn-floating btn-large 
			waves-effect waves-light amber darken-2'>
			<i class='material-icons'>mode_edit</i></a> <a name = '".$rows[$c]["claveSolicitud"]."' class='btnEliminarSolicitudLab btn-floating btn-large 
			waves-effect waves-light red darken-1'><i class='material-icons'>
			delete</i></a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function solicitudesRealizadas ()
{
	//MODIFICAR
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$maestro	= $_SESSION['nombre'];
		$mat 		= "";
		$materias 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select c.claveCalendarizacion, s.MATCVE, p.tituloPractica, l.nombreLaboratorio, c.fechaAsignada, c.horaAsignada from lbcalendarizaciones c INNER JOIN lbsolicitudlaboratorios s ON s.claveSolicitud = c.claveSolicitud INNER JOIN lblaboratorios l ON l.claveLaboratorio = s.claveLaboratorio INNER JOIN lbpracticas p on p.clavePractica = s.clavePractica WHERE c.PDOCVE = '2161' AND s.claveUsuario =%d AND c.estatus = 'R'",$maestro);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$mat 	.= "'".($row["MATCVE"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$mat = (rtrim($mat,","));
		$materias = nomMat($mat);
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$materias[$rows[$c]["MATCVE"]]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreLaboratorio"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function mostrarSolicitud($clave,$solicitud)
{
	$periodo 	= periodoActual();
	$respuesta 	= false;
	$rows 		= array();
	$maestro	= $clave;
	$solId      = $solicitud;
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select s.claveSolicitud, s.MATCVE, s.GPOCVE, s.fechaSolicitud, p.tituloPractica, s.horaSolicitud, s.cantidadAlumnos, l.nombreLaboratorio from lbsolicitudlaboratorios s inner join lbpracticas p on s.clavePractica = p.clavePractica inner join lblaboratorios l on s.claveLaboratorio = l.claveLaboratorio left join lbcalendarizaciones c ON c.claveSolicitud = s.claveSolicitud where s.PDOCVE =%s and s.claveUsuario =%d and s.claveSolicitud =%d and c.claveSolicitud is NULL",$periodo,$maestro,$solId);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{	
		//$respuesta 	= true;
		//$rows		= $row;
		return $row;
	}
	else
	{
		return " ";
	}
	/*$arrayJSON = array('respuesta' => $respuesta,
		'rows' => $rows);
	print json_encode($arrayJSON);*/
}
function editarSolicitud ()
{
	session_start();
	$combo   		= "";
	$clave  		= $_SESSION['nombre'];
	$solId 			= GetSQLValueString($_POST['solId'],"int");
	$solicitud  	= mostrarSolicitud($clave,$solId);
	$materias 		= materias(claveMaestro($clave));
	$respuesta 		= false;
	if($solicitud!= " ")
		$respuesta = true;
		$combo .= "<option value='Ingenieria Web'>'Ingenieria Web'</option>";
	$arrayJSON = array('respuesta' => $respuesta,
						'combo' => $combo);
	print json_encode($arrayJSON);
}
function eliminarSolicitud ()
{
	$claveSol 			= GetSQLValueString($_POST["solId"],"int");
	$respuesta 			= false;
	if(existeSol($claveSol))
	{
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("update lbsolicitudlaboratorios set estatus = 'B' where claveSolicitud =%d",$claveSol);
		$res 	 		=  mysql_query($consulta);
		if($res)
		{	
			$respuesta = true;
		}
		$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
	}
}
function agregarArt ()
{
	$cveArt 	= GetSQLValueString($_POST['artCve'],"text");
	$nomArt 	= $_POST['artNom'];
	$respuesta	= true;
	$renglones	= "";
	$renglones .= "<tr id=".$cveArt.">";
	$renglones .= "<td class='col s2'><input type='number' min='1' max='20' value='1'></td>";
	$renglones .= "<td class='col s8'>".$nomArt."</td>";
	$renglones .= "<td class='col s2'><a name =".$cveArt."class='btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1'><i class='material-icons'>delete</i></a></td>";
	$renglones .= "</tr>";
	$arrayJSON = array('respuesta' => $respuesta,
						'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function nuevaSol1(){
	$respuesta	= false;
	$conexion 	= conectaBDSICLAB();
 	$consulta = sprintf("select * from lbcalendarizaciones limit 1");
	$res 	  = mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{	
		$respuesta 	= true;
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function nuevaSol()
{
	//insertar en lbsolicitudes
	session_start();
	$respuesta	= false;
	$cveDep 	= GetSQLValueString("0000","text"); //preparado para cuando se
	$periodo 	= GetSQLValueString(periodoActual(),"text");
	$fe 		= GetSQLValueString($_POST['fe'],"text");
	$fs 		= GetSQLValueString($_POST['fs'],"text");
	$hs 		= GetSQLValueString($_POST['hs'],"text");
	$lab 		= GetSQLValueString($_POST['lab'],"text");
	$uso 		= GetSQLValueString($_POST['uso'],"text");
	$prac 		= GetSQLValueString($_POST['prac'],"int"); 		
	$clave  	= GetSQLValueString(($_SESSION['nombre']),"int");
	$firma 		= GetSQLValueString("123456","text");
	$mat 		= GetSQLValueString($_POST['mat'],"text");
	$gpo 		= GetSQLValueString($_POST['gpo'],"int");
	$gp 		= GetSQLValueString(grupo($mat,$clave,$periodo,$gpo),"text");//insertar
	$cant 		= GetSQLValueString($_POST['cant'],"int");
	$estatus  	= GetSQLValueString("V","text");
	$b 			= GetSQLValueString(" ","text");
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("insert into lbsolicitudlaboratorios values(%s,%s,%s,%s,%s,%s,%s,%s,%d,%d,%s,%s,%s,%s,%s)",$cveDep,$periodo,$b,$fe,$fs,$hs,$lab,$uso,$prac,$clave,$firma,$mat,$gp,$cant,$estatus);
	$res 		= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		$respuesta = true; 
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'usuario1':
	usuario();
	break;
	case 'solicitudesAceptadas1':
	solicitudesAceptadas();
	break;
	case 'liberarPractica1':
	liberarPractica();
	break;
	case 'solicitudesPendientes1':
	solicitudesPendientes();
	break;
	case 'solicitudesRealizadas1':
	solicitudesRealizadas();
	break;
	case 'editarSolicitud1':
	editarSolicitud();
	break;
	case 'eliminarSolicitud1':
	eliminarSolicitud();
	break;
	case 'agregarArt1':
		agregarArt();
		break;
	case 'nuevaSol1':
		nuevaSol();
		break;
} 
?>