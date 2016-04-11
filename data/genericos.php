<?php
require_once('../data/conexion.php');
function usuario ()
{
	session_start();
	$_SESSION['nombre'] = GetSQLValueString($_POST['clave1'],"text");
}
function salir()
{
	session_start();
	session_destroy();
	$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function existeSol ($clave)
{
		$claveSol	= $clave;
		$conexion 	= conectaBDSICLAB();
		$consulta 	= sprintf("select claveCalendarizacion from lbcalendarizaciones 
			where claveCalendarizacion =%s",$claveSol);
		$res 		= mysql_query($consulta); 
		if($row = mysql_fetch_array($res))
		{
			return true;
		}
		return false;
}
function existeSolLab ($clave)
{
		$claveSol	= $clave;
		$conexion 	= conectaBDSICLAB();
		$consulta 	= sprintf("select claveSolicitud from lbsolicitudlaboratorios 
			where claveSolicitud =%s",$claveSol);
		$res 		= mysql_query($consulta); 
		if($row = mysql_fetch_array($res))
		{
			return true;
		}
		return false;
}
//pendiente de terminar
function pendientesLaboratorio()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$maestro	= $_SESSION['nombre'];
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$solPendientesLab ="";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select s.claveSolicitud,s.GPOCVE,p.tituloPractica,s.fechaSolicitud,s.horaSolicitud from lbusuarios as u INNER JOIN lbsolicitudlaboratorios as s ON u.claveUsuario =s.claveUsuario INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica");
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='maestro'>Maestro</th>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		
		while($row = mysql_fetch_array($res))
		{
			$solPendientesLab .="'".($row["claveSolicitud"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$solPendientesLab = (rtrim($solPendientesLab,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["GPOCVE"]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect  green darken-2' type='button' id='btnCalendarizado'><i class='material-icons'>done</i></a></td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas'><i class='material-icons'>add</i></a></td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect red darken-1' id='btnEliminarSolLab'><i class='material-icons'>delete</i></a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
//ver mas y ver mas dos falta regresar los datos de las consultas a las pantallas
function verMas()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$clave 		= GetSQLValueString($_POST["clave"],"text");
		$renglones		="";
		$fechaSolicitud	="";
		$horaSolicitud	="";
		$con 			="";
		$grupo			="";
		$nombrePractica ="";
		$nombreArticulo ="";
		$cantidad 		="";
		$rows		= array();
		$conexion 		= conectaBDSICLAB();
//se necesita agregar un estatus en lbsolicitudlaboratorios ya que 
//no se sabe si no fue aceptada, si esta calendarizada o aun esta pendiente
		if(existeSolLab($clave))
		{
			$consulta  		= sprintf("select a.fechaSolicitud,a.horaSolicitud,a.GPOCVE,c.tituloPractica, b.nombreArticulo, d.cantidad 
				from lbarticuloscat as b INNER JOIN lbasignaarticulospracticas as d ON b.claveArticulo=d.claveArticulo
			 INNER JOIN lbsolicitudlaboratorios as a ON d.claveSolicitud=a.claveSolicitud 
			 INNER JOIN lbpracticas as c ON a.clavePractica=c.clavePractica
				where a.claveSolicitud =%s",$clave);
			$renglones	.= "<thead>";
			$renglones	.= "<tr>";
			$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
			$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
			$renglones	.= "</tr>";
			$renglones	.= "</thead>";
			$res 	 	=  mysql_query($consulta);

			while($row = mysql_fetch_array($res))
			{	
				$respuesta = true;
				$fechaSolicitud = $row["fechaSolicitud"];
				$horaSolicitud = $row["horaSolicitud"];
				$grupo = $row["GPOCVE"];
				$nombrePractica = $row["tituloPractica"];
				$rows[]=$row;
				$con++;
				
			}
			for($c= 0; $c< $con; $c++)
			{
				$renglones .= "<tbody>";
				$renglones .= "<tr>";
				$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
				$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
				$renglones .= "</tr>";
				$renglones .= "</tbody>";
				$respuesta = true;
			}
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta, 'fecha' =>$fechaSolicitud, 'hora' => $horaSolicitud,'maestro' => $grupo, 'practica' => $nombrePractica, 'renglones' => $renglones);
		print json_encode($arrayJSON);
}
//obtiene los datos de la solicitud para mostrar en la pantalla guardaSolLab
function obtenerDatosSolLab()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$clave 		= GetSQLValueString($_POST["clave"],"text");
		$fecha 			= "";
		$hora 			= "";
		$conexion 		= conectaBDSICLAB();
		if(existeSolLab($clave))
		{
			$consulta  	= sprintf("select fechaSolicitud,horaSolicitud from lbsolicitudlaboratorios where claveSolicitud=%s",$clave);
			$res 	 	=  mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{
				$respuesta = true;
				$fecha	= $row["fechaSolicitud"];
				$hora	= $row["horaSolicitud"];
			}
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta, 'fecha' => $fecha, 'hora' => $hora);
		print json_encode($arrayJSON);
}
//Se inserta la solicitud en la tabla lbcalendarizaciones
function guardaSolicitudLab()
{
	//falta obtener el periodo
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$clave 			= GetSQLValueString($_POST["clave"],"text");
		$claveCal		= GetSQLValueString($_POST["claveCal"],"text");
		$estatus 		= GetSQLValueString($_POST["estatus"],"text");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$hora 			= GetSQLValueString($_POST["hora"],"text");
		$firmaJefe 		= GetSQLValueString($_POST["firmaJefe"],"int");
		$comentarios	= GetSQLValueString($_POST["comentarios"],"text");
		$conexion 		= conectaBDSICLAB();
		if(existeSolLab($clave))
		{
			$consulta  		= sprintf("insert into lbcalendarizaciones values(%s,%s,%s,%s,%d,%s,%s,%s)",$periodo,$claveCal,$fecha,$hora,$firmaJefe,$estatus,$comentarios,$clave);
			$res 	 	=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
			$respuesta = true; 
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
//eliminar una solicitud de laboratorio, pendiente por las relaciones
//no se realiza aun
function eliminaSolicitudLab()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$clave 		= GetSQLValueString($_POST["clave"],"text");
		$conexion 		= conectaBDSICLAB();
		if(existeSolLab($clave))
		{
			$consulta  	= sprintf("delete from lbasignaarticulospracticas,lbsolicitudlaboratorios where claveSolicitud=%s",$clave);
			$res 	 	=  mysql_query($consulta);
			/*$consulta2 = sprintf("delete from lbsolicitudlaboratorios where claveSolicitud=%s",$clave)
			$res2 	 	=  mysql_query($consulta2);*/
			if($res)
			{
				$respuesta = true;

			}
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
//funcion para sacar la lista de las solicitudes calendarizadas
function aceptadasLaboratorio()
{
	$respuesta 	= true;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$solAceptadasLab ="";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select s.claveSolicitud,s.GPOCVE,p.tituloPractica,s.fechaSolicitud,s.horaSolicitud 
			from lbpracticas as p INNER JOIN lbsolicitudlaboratorios as s ON p.clavePractica=s.clavePractica 
			INNER JOIN lbcalendarizaciones as c ON s.claveSolicitud=c.claveSolicitud");
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='maestro'>Maestro</th>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		
		while($row = mysql_fetch_array($res))
		{
			$solAceptadasLab .="'".($row["claveSolicitud"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$solAceptadasLab = (rtrim($solAceptadasLab,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["GPOCVE"]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas2'><i class='material-icons'>add</i></a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function verMas2()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$claveCal 		= GetSQLValueString($_POST["clave"],"text");
		$renglones		="";
		$fechaAsignada	="";
		$horaAsignada	="";
		$con 			="";
		$grupo			="";
		$practica 		="";
		$nombreArticulo ="";
		$cantidad 		="";
		$rows		= array();
		$conexion 		= conectaBDSICLAB();

		if(existeSolLab($claveCal))
		{
			$consulta  		= sprintf("select a.claveCalendarizacion, a.fechaAsignada, a.horaAsignada, s.GPOCVE, b.nombreArticulo, d.cantidad 
			from lbarticuloscat as b INNER JOIN lbasignaarticulospracticas as d ON b.claveArticulo=d.claveArticulo
			INNER JOIN lbsolicitudlaboratorios as s ON d.claveSolicitud=s.claveSolicitud 
			INNER JOIN lbcalendarizaciones as a ON s.claveSolicitud=a.claveSolicitud 
			where s.claveSolicitud=%s",$claveCal);
			$renglones	.= "<thead>";
			$renglones	.= "<tr>";
			$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
			$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
			$renglones	.= "</tr>";
			$renglones	.= "</thead>";
			$res 	 	=  mysql_query($consulta);

			while($row = mysql_fetch_array($res))
			{	
				$respuesta = true;
				$fechaAsignada = $row["fechaAsignada"];
				$horaAsignada = $row["horaAsignada"];
				$grupo = $row["GPOCVE"];
				$practica= $row["claveCalendarizacion"];
				$rows[]=$row;
				$con++;
				
			}
			for($c= 0; $c< $con; $c++)
			{
				$renglones .= "<tbody>";
				$renglones .= "<tr>";
				$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
				$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
				$renglones .= "</tr>";
				$renglones .= "</tbody>";
				$respuesta = true;
			}
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta, 'fechaAsignada' =>$fechaAsignada, 'horaAsignada' => $horaAsignada,
		'maestro' => $grupo, 'practica' => $practica, 'renglones' => $renglones);
		print json_encode($arrayJSON);
}
function listaArticulos()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select A.claveArticulo,B.nombreArticulo, C.cantidad from lbarticulos as A inner join lbarticuloscat as B ON A.claveArticulo=B.claveArticulo inner join lbinventarios as C ON B.claveArticulo=C.claveArticulo where A.estatus='V' GROUP BY C.claveArticulo",$responsable);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='codigo'>Código</th>";
		$renglones	.= "<th data-field='nombreArticulo'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$art 	.= "'".($row["claveArticulo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$art = (rtrim($art,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function altaInventario1 ()
{
	//$cveUsuario		= GetSQLValueString($_POST[""],"text");
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$conexion					= conectaBDSICLAB();
		$imagen						= GetSQLValueString($_POST["imagen"],"text");
		$identificadorArticulo 		= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$modelo						= GetSQLValueString($_POST["modelo"],"text");
		$numeroSerie				= GetSQLValueString($_POST["numeroSerie"],"text");
		$marca						= GetSQLValueString($_POST["marca"],"text");
		$tipoContenedor				= GetSQLValueString($_POST["tipoContenedor"],"text");
		$descripcionArticulo		= GetSQLValueString($_POST["descripcionArticulo"],"text");
		$descripcionUso				= GetSQLValueString($_POST["descripcionUso"],"text");
		$unidadMedida				= GetSQLValueString($_POST["unidadMedida"],"text");
		$fechaCaducidad				= GetSQLValueString($_POST["fechaCaducidad"],"text");
		$claveKit					= GetSQLValueString($_POST["claveKit"],"text");
		$ubicacionAsignada			= GetSQLValueString($_POST["ubicacionAsignada"],"text");
		$claveArticulo 				= GetSQLValueString($_POST["claveArticulo"],"text");	
		//$claveArticulo = claveArt(nombreArt);
		$estatus 					= GetSQLValueString($_POST["estatus"],"text");
		//insert a tabla lbarticulos
		$consulta= sprintf("insert into lbarticulos values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			$claveArticulo,$descripcionUso,$descripcionArticulo,$numeroSerie,$marca,$modelo,$estatus,$unidadMedida,$fechaCaducidad,$tipoContenedor,$imagen,$identificadorArticulo,$ubicacionAsignada,$claveKit);
		$resconsulta = mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true; 
	}
	else
	{
		//salir();
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
	
}
function buscaArticulo()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$identificadorArticulo= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$rows			= array();
		$modelo			= "";
		$numeroSerie	= "";
		$nombreArticulo	= "";
		$marca			= "";
		$fechaCaducidad	= "";
		$descripcionArticulo	= "";
		$descripcionUso	= "";
		$unidadMedida	= "";
		$tipoContenedor	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select A.modelo,A.numeroSerie,B.nombreArticulo,A.marca,A.fechaCaducidad,
			A.descripcionArticulo,A.descripcionUso,A.unidadMedida, A.tipoContenedor
			from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
			where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo,$responsable);
		$res 		= mysql_query($consulta);
		
		while($row = mysql_fetch_array($res))
		{
			$respuesta = true;
			$modelo			= $row["modelo"];
			$numeroSerie 	= $row["numeroSerie"];
			$nombreArticulo	= $row["nombreArticulo"];
			$marca		 	= $row["marca"];
			$fechaCaducidad	= $row["fechaCaducidad"];
			$descripcionArticulo	= $row["descripcionArticulo"];
			$descripcionUso	= $row["descripcionUso"];
			$unidadMedida	= $row["unidadMedida"];
			$tipoContenedor	= $row["tipoContenedor"];
		}
	}
	$salidaJSON = array('respuesta' => $respuesta,
		'modelo' => $modelo, 'numeroSerie' => $numeroSerie, 'nombreArticulo' => $nombreArticulo,
		'marca' => $marca, 'fechaCaducidad' => $fechaCaducidad, 'descripcionArticulo' => $descripcionArticulo,
		'descripcionUso' => $descripcionUso, 'unidadMedida' => $unidadMedida, 'tipoContenedor' => $tipoContenedor);
	print json_encode($salidaJSON);
}
function bajaArticulos()
{
	$respuesta = false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
		$estatus				= GetSQLValueString($_POST["estatus"],"text");
		$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
		$conexion 	= conectaBDSICLAB();
		$consulta1	= sprintf("update lbarticulos set estatus='B' where identificadorArticulo=%d",$identificadorArticulo,$responsable);
		$res = mysql_query($consulta1);
		if(mysql_affected_rows()>0)
			$respuesta = true;
		
		/*else if ($estatus == 'MR') 
		{
			$consulta2	= sprintf("update lbmovimientosarticulos set estatus='MR' where identificadorArticulo=%d",$identificadorArticulo,$responsable);
			$res 		= mysql_query($consulta2);
			if(mysql_affected_rows()>0)
			$respuesta = true;
		} */
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function listaMantenimiento()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select ma.claveMovimiento,B.nombreArticulo, C.identificadorArticulo from lbarticuloscat B
			inner join lbarticulos as C ON B.claveArticulo=C.claveArticulo inner join lbmovimientosarticulos ma
			ON ma.identificadorArticulo=C.identificadorArticulo where ma.estatus='M'",$responsable);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='codigoBarras'>Código de barras</th>";
		$renglones	.= "<th data-field='nombreArticulo'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$art 	.= "'".($row["identificadorArticulo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$art = (rtrim($art,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveMovimiento"]."' class='btn-floating btn-large waves-effect  green darken-2' id='btnRegresaDelMtto'><i class='material-icons'>done</i></a></td>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function mantenimientoArticulos()
{
	$respuesta = false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable 			= $_SESSION['nombre'];
		$resp 					= GetSQLValueString($_POST["respons"],"text");
		$periodo				= GetSQLValueString($_POST["periodo"],"text");
		$estatus				= GetSQLValueString($_POST["estatus"],"text");
		$claveMovimiento		= GetSQLValueString($_POST["claveMovimiento"],"text");
		$claveLab				= GetSQLValueString($_POST["claveLab"],"text");
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
		$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
		$fechaMovimiento		= GetSQLValueString($_POST["fechaMovimiento"],"text");
		$horaMovimiento			= GetSQLValueString($_POST["horaMovimiento"],"text");
		$conexion 	= conectaBDSICLAB();

		$consulta 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%s,%s,%s,%s,%s,%s)",
						$periodo,$fechaMovimiento,$horaMovimiento,$resp,$identificadorArticulo,$observaciones,
						$estatus,$claveMovimiento,$claveLab);
		//$consulta1	= sprintf("update lbmovimientosarticulos set estatus='M', where identificadorArticulo=%d",$identificadorArticulo,$responsable);
		$res = mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function buscaArticuloMtto()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$identificadorArticulo= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$modelo			= "";
		$numeroSerie	= "";
		$nombreArticulo	= "";
		$marca			= "";
		$fechaCaducidad	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select A.modelo,A.numeroSerie,B.nombreArticulo,A.marca,A.fechaCaducidad
			from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
			where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo,$responsable);
		$res 		= mysql_query($consulta);
		while($row = mysql_fetch_array($res))
		{
			$respuesta = true;
			$modelo			= $row["modelo"];
			$numeroSerie 	= $row["numeroSerie"];
			$nombreArticulo	= $row["nombreArticulo"];
			$marca		 	= $row["marca"];
			$fechaCaducidad	= $row["fechaCaducidad"];
		}
	}
	$salidaJSON = array('respuesta' => $respuesta
		,'modelo' => $modelo, 'numeroSerie' => $numeroSerie, 'nombreArticulo' => $nombreArticulo,
		'marca' => $marca, 'fechaCaducidad' => $fechaCaducidad
		);
	print json_encode($salidaJSON);
}
function peticionesPendientesArt()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select p.clavePedido,p.DEPCVE,p.nombreArticulo,p.cantidad 
								from lbpedidos p 
								where estatus='P'",$responsable);
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$art 	.= "'".($row["clavePedido"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$art = (rtrim($art,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["DEPCVE"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePedido"]."' class='btn-floating btn-large waves-effect  green darken-2' id='btnAceptaPeticion'><i class='material-icons'>done</i></a></td>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function prestamosPendientes()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select ea.ALUCTR,ea.horaEntrada,ea.fechaEntrada,p.clavePrestamo 
								from lbentradasalumnos ea 
								INNER JOIN lbprestamos p on ea.ALUCTR=p.ALUCTR
								inner join lbsolicitudarticulos sa on p.clavePrestamo=sa.clavePrestamo
								where sa.estatus='S' GROUP BY p.clavePrestamo");
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
		$renglones	.= "<th data-field='nombre'>Nombre</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$prestamo 	.= "'".($row["clavePrestamo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$prestamo = (rtrim($prestamo,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
			$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaEntrada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaEntrada"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light  green darken-2' id='btnAtenderPrestamo'>Atender</a></td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light red darken-1 eliminarPrestamo' id='btnEliminarPrestamo' type='submit'>Eliminar</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function atenderPrestamo()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$clavePrestamo = GetSQLValueString($_POST["clavePrestamo"],"text");
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$nombre		= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select p.aluctr,p.clavePrestamo,ac.claveArticulo,ac.nombreArticulo,sa.cantidad 
								from lbarticuloscat ac 
								INNER JOIN lbsolicitudarticulos sa on sa.claveArticulo=ac.claveArticulo 
								INNER JOIN lbprestamos p on p.clavePrestamo=sa.clavePrestamo
								where sa.estatus='S'");
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "<th data-field='descripcion'>Descripcion</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$prestamo 	.= "'".($row["clavePrestamo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$prestamo = (rtrim($prestamo,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function agregaArticulos()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable			= $_SESSION['nombre'];
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$idu 					= "";
		$nomArt 				= "";
		$renglones				= "";
		$conexion 				= conectaBDSICLAB();
		$consulta				= sprintf("select A.identificadorArticulo,B.nombreArticulo,B.claveArticulo
											from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
											where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo,$responsable);
		$res 					= mysql_query($consulta);
		
		if($row = mysql_fetch_array($res))
		{
			$idu  		=$row["identificadorArticulo"];
			$nomArt 	=$row["nombreArticulo"];
			$respuesta 	= true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta,
						 'idu' 		=> $idu,
						 'nomArt' 	=> $nomArt);
	print json_encode($salidaJSON);
}
 function guardaPrestamoPendiente()
 {
 	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$responsable	= $_SESSION['nombre'];
		$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"text");
		$listaArt		= $_POST['listaArt'];
		$arrayArt 		= explode(',',$listaArt); 
		$cantidad 		= count($arrayArt);
		for ($i=0; $i < $cantidad ; $i++) { 
			$conexion 	= conectaBDSICLAB();
			$consulta	= sprintf(" insert into lbprestamosarticulos values(%s,%s,%s,%s)",'""',$arrayArt[$i],$clavePrestamo,'"P"');
			$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
 }
function eliminaPrestamoPendiente()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$responsable 	= $_SESSION['nombre'];
		$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"text");
		$conexion 		= conectaBDSICLAB();
		$consulta	= sprintf("update lbsolicitudarticulos set estatus='N' 
								where clavePrestamo=%s",$clavePrestamo);
		$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function prestamosProceso()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select ea.ALUCTR,p.fechaPrestamo,p.horaPrestamo, p.clavePrestamo 
								from lbentradasalumnos ea 
								inner join lbprestamos p ON ea.ALUCTR = p.ALUCTR 
								inner join lbprestamosarticulos pa ON pa.clavePrestamo=p.clavePrestamo
								where estatus='P' GROUP BY p.clavePrestamo");
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
		$renglones	.= "<th data-field='nombre'>Nombre</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$prestamo 	.= "'".($row["clavePrestamo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$prestamo = (rtrim($prestamo,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
			$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaPrestamo"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaPrestamo"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='waves-effect waves-light btn amber darken-2' id='btnDevolucionMaterial'>Devolución</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function devolucionPrestamo()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable 	= $_SESSION['nombre'];
		$prestamo		= "";
		$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"text");
		$con 			= 0;
		$rows			= array();
		$renglones		= "";
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select p.clavePrestamo,ac.nombreArticulo,a.identificadorArticulo 
								from lbarticuloscat ac 
								INNER JOIN lbarticulos a ON ac.claveArticulo=a.claveArticulo
								INNER JOIN lbprestamosarticulos pa on a.identificadorArticulo=pa.identificadorArticulo
								INNER JOIN lbprestamos p on p.clavePrestamo=pa.clavePrestamo
								where pa.estatus='P'");
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='cantidad'>Código</th>";
		$renglones	.= "<th data-field='descripcion'>Nombre el artículo</th>";
		$renglones	.= "<th data-field='descripcion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$prestamo 	.= "'".($row["identificadorArticulo"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$prestamo = (rtrim($prestamo,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='btn waves-effect waves-light green darken-2 devolucionArt' id='btnDevolverArt'>Devolver</a></td>";
			$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='waves-effect waves-light btn amber darken-2' id='btnAplicaSancion'>Sancionar</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta, 
						'renglones' => $renglones, 
						'clavePrestamo' => $clavePrestamo);
	print json_encode($salidaJSON);
}
function guardaDevolucion()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$responsable			= $_SESSION['nombre'];
		$clavePrestamo 			= GetSQLValueString($_POST["clavePrestamo"],"text");
		$identificadorArticulo 	= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$fecha 					= GetSQLValueString($_POST["fechaDevolucion"],"text");
		$hora 					= GetSQLValueString($_POST["horaDevolucion"],"text");
		$depto 					= "1234";
		$periodo 				= "9898";
		$conexion 				= conectaBDSICLAB();
		$consulta  				= sprintf("insert into lbdevoluciones values(%s,%s,%s,%s,%s,%s)",
									$periodo,$clavePrestamo,$identificadorArticulo,$responsable,$fecha,$hora);
		$res 	 				=  mysql_query($consulta);
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
function aplicaSancion()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function guardaSancion()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{

	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function listaAlumnosSancionados()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select sa.ALUCTR,sa.inicioSancion,sa.finSancion,sa.comentarios,s.claveSancion,ac.nombreArticulo 
							from lbsanciones s 
							inner join lbasignasanciones sa ON sa.claveSancion=s.claveSancion 
							INNER JOIN lbarticulos art ON art.identificadorArticulo=sa.identificadorArticulo 
							INNER JOIN lbarticuloscat ac ON ac.claveArticulo=art.claveArticulo");
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
		$renglones	.= "<th data-field='articulo'>Artículo</th>";
		$renglones	.= "<th data-field='fecha'>Inicio sanción</th>";
		$renglones	.= "<th data-field='hora'>Fin sanción</th>";
		$renglones	.= "<th data-field='comentarios'>Comentarios</th>";
		$renglones	.= "<th data-field='accion'>Acción</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$prestamo 	.= "'".($row["claveSancion"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$prestamo = (rtrim($prestamo,","));
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["inicioSancion"]."</td>";
			$renglones .= "<td>".$rows[$c]["finSancion"]."</td>";
			$renglones .= "<td>".$rows[$c]["comentarios"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSancion"]."' class='btn waves-effect waves-light green darken-2' id='btnQuitaSancion'>Quitar</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	else
	{
		//salir();
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function quitaSanciones()
{
	$respuesta 	= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{ 
		$respuesta = true;
	}
	else
	{
		//salir();
	}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function guardaPeticionArticulos()
{
	$respuesta 		= false;
	session_start();
	if(!empty($_SESSION['nombre']))
	{
		$responsable	= $_SESSION['nombre'];
		$nombreArticulo = GetSQLValueString($_POST["nombreArticulo"],"text");
		$cantidad		= GetSQLValueString($_POST["cantidad"],"text");
		$marca 			= GetSQLValueString($_POST["marca"],"text");
		$modelo 		= GetSQLValueString($_POST["modelo"],"text");
		$motivo 		= GetSQLValueString($_POST["motivo"],"text");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$depto 			= "1234";
		$firma 			= "0000";
		$periodo 		= "9898";
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("insert into lbpedidos values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$periodo,'""',$fecha,$depto,$firma,$responsable,$nombreArticulo,$cantidad,$motivo,$marca,$modelo,'"P"');
		$res 	 		=  mysql_query($consulta);
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
	case 'altaInventario1':
	altaInventario1();
	break;
	case 'listaArticulos1':
	listaArticulos();
	break;
	case 'usuario1':
	usuario();
	break;
	case 'bajaArticulos1':
	bajaArticulos();
	break;
	case 'buscaArticulos1':
	buscaArticulo();
	break;
	case 'listaMantenimiento1':
	listaMantenimiento();
	break;
	case 'mantenimientoArticulos1':
	mantenimientoArticulos();
	break;
	case 'buscaArticulos2':
	buscaArticuloMtto();
	break;
	case 'peticionesPendientesArt1':
	peticionesPendientesArt();
	break;
	case 'pendientesLab1':
	pendientesLaboratorio();
	break;
	case 'aceptadasLab1':
	aceptadasLaboratorio();
	break;
	case 'verMasLab1':
	verMas();
	break;
	case 'verMasLab2':
	verMas2();
	break;
	case 'obtenerDatosSolLab1':
	obtenerDatosSolLab();
	break;
	case 'guardaSolicitudLab1':
	guardaSolicitudLab();
	break;
	case 'eliminaSolicitudLab1':
	eliminaSolicitudLab();
	break;
	case 'prestamosPendientes1':
	prestamosPendientes();
	break;
	case 'atenderPrestamo1':
	atenderPrestamo();
	break;
	case 'agregaArticulos1':
	agregaArticulos();
	break;
	case 'guardaPrestamoPendiente1':
	guardaPrestamoPendiente();
	break;
	case 'eliminaPrestamoPendiente1':
	eliminaPrestamoPendiente();
	break;
	case 'prestamosProceso1':
	prestamosProceso();
	break;
	case 'devolucionPrestamo1':
	devolucionPrestamo();
	break;
	case 'guardaDevolucion1':
	guardaDevolucion();
	break;
	case 'aplicaSancion1':
	aplicaSancion();
	break;
	case 'guardaSancion1':
	guardaSancion();
	break;
	case 'listaSanciones1':
	listaAlumnosSancionados();
	break;
	case 'quitaSanciones1':
	quitaSanciones();
	break;
	case 'salir1':
	salir();
	break;
	case 'guardaPeticionArticulos1':
	guardaPeticionArticulos();
	break;
} 
?>