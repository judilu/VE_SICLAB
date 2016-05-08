<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');

function usuario ()
{
	$usuario = GetSQLValueString($_POST['clave1'],"int");
	$usuario = substr($usuario, -2,1);
	session_start();
	$_SESSION['nombre'] = (int)$usuario;
}
function salirG()
{
	session_start();
	session_destroy();
	$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function existeSolLabG ($clave)
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
function nomMae($clave)
{
	$claveUsuario 	= $clave;
	$maestro 		= claveMaestro($claveUsuario);
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select PERCVE, PERNOM, PERAPE from DPERSO where PERCVE =%d",$maestro);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["PERNOM"]." ".$row["PERAPE"];
	}
	else
	{
		return "";
	}
}
function nombreMat($clave)
{
	$claveMat 	= GetSQLValueString($clave,"text");
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select MATCVE, MATNCO from DMATER where MATCVE=%s",$claveMat);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["MATNCO"];
	}
	else
	{
		return "";
	}
}
function claveResp($usuario)
{
	$claveUsuario 	= $usuario;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select claveResponsable from lbresponsables where claveUsuario=%d",$claveUsuario);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveResponsable"];
	}
	else
	{
		return "";
	}

}
//Solicitudes pendientes de laboratorio
function pendientesLaboratorio()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$maestro 	= "";
		$percve 	= "";
		$nomMaestro = "";
		$nomMat 	= "";
		$tipoUsuario= tipoUsuario($responsable);
		$solPendientesLab ="";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select s.claveSolicitud,s.MATCVE,p.tituloPractica,s.fechaSolicitud,s.horaSolicitud,s.claveUsuario 
								from lbusuarios as u 
								INNER JOIN lbsolicitudlaboratorios as s ON u.claveUsuario =s.claveUsuario 
								INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
								where s.estatus='V' and NOT EXISTS (select * from lbcalendarizaciones as c where c.claveSolicitud=s.claveSolicitud)");
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
			$maestro 	= $rows[$c]["claveUsuario"];
			$nomMaestro = nomMae($maestro);
			$cveGpo 	= $rows[$c]["MATCVE"];
			$nomMat 	= nombreMat($cveGpo);
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$nomMaestro."</td>";
			$renglones .= "<td>".$nomMat."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect  green darken-2' type='button' id='btnCalendarizado'><i class='material-icons'>done</i></a></td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas'><i class='material-icons'>add</i></a></td>";
			if($tipoUsuario != 3)
			{
				$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect red darken-1' id='btnEliminarSolLab'><i class='material-icons'>delete</i></a></td>";
			}
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$arrayJSON = array('respuesta' 	=> $respuesta,
						'renglones' => $renglones,
						'contador' 	=> $con);
	print json_encode($arrayJSON);
}
//Ver mas de las solicitudes pendientes del laboratorio
function verMas()
{
	$respuesta 		= false;
	session_start();
		$clave 			= GetSQLValueString($_POST["clave"],"int");
		$renglones		= "";
		$fechaSolicitud	= "";
		$horaSolicitud	= "";
		$con 			= "";
		$maestro		= "";
		$nombrePractica = "";
		$nombreArticulo = "";
		$cantidad 		= "";
		$usuario 		= 0;
		$rows			= array();
		$conexion 		= conectaBDSICLAB();
		if(existeSolLabG($clave))
		{
			$consulta  		= sprintf("select sl.fechaSolicitud,sl.horaSolicitud,sl.MATCVE,p.tituloPractica, ac.nombreArticulo, aa.cantidad, sl.claveUsuario 
									from lbarticuloscat as ac
									INNER JOIN lbasignaarticulospracticas as aa ON ac.claveArticulo=aa.claveArticulo
									INNER JOIN lbsolicitudlaboratorios as sl ON sl.claveSolicitud=aa.claveSolicitud 
									INNER JOIN lbpracticas as p ON sl.clavePractica=p.clavePractica
									where sl.claveSolicitud =%s 
									AND sl.claveSolicitud NOT IN(select c.claveSolicitud from lbcalendarizaciones c where c.claveSolicitud=%s)",$clave,$clave);
			$renglones	.= "<thead>";
			$renglones	.= "<tr>";
			$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
			$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
			$renglones	.= "</tr>";
			$renglones	.= "</thead>";
			$res 	 	=  mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{	
				$respuesta		= true;
				$fechaSolicitud = $row["fechaSolicitud"];
				$horaSolicitud 	= $row["horaSolicitud"];
				$usuario 		= $row["claveUsuario"];
				$maestro 		= nomMae($usuario);
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
	$arrayJSON = array('respuesta' => $respuesta, 
						'fecha' =>$fechaSolicitud, 
						'hora' => $horaSolicitud,
						'maestro' => $maestro, 
						'practica' => $nombrePractica, 
						'renglones' => $renglones);
		print json_encode($arrayJSON);
}
//obtiene los datos de la solicitud para mostrar en la pantalla guardaSolLab
function obtenerDatosSolLab()
{
	$respuesta 		= false;
	session_start();
		$clave 			= GetSQLValueString($_POST["clave"],"int");
		$fecha 			= "";
		$hora 			= "";
		$conexion 		= conectaBDSICLAB();
		if(existeSolLabG($clave))
		{
			$consulta  	= sprintf("select fechaSolicitud,horaSolicitud from lbsolicitudlaboratorios where claveSolicitud=%d",$clave);
			$res 	 	=  mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{
				$respuesta = true;
				$fecha	= $row["fechaSolicitud"];
				$hora	= $row["horaSolicitud"];
			}
		}
	$arrayJSON = array('respuesta' => $respuesta, 'fecha' => $fecha, 'hora' => $hora);
		print json_encode($arrayJSON);
}
//consulta si la fecha de la solicitud de laboratorio esta disponible
function consultaFechaSol($fecha,$hora,$periodo)
{
	$fechaSolicitada 	= $fecha;
	$horaSolicitada 	= $hora;
	$fechaSolicitada2 	= str_replace("/", "-", $fechaSolicitada);
	$fechaSolicitada2 	= str_replace("'", "", $fechaSolicitada2);
	$fechaSolicitada2 	= strtotime($fechaSolicitada2);
	$periodoSol			= $periodo;
	$fecha_hoy 			= strtotime(date("d-m-Y"));
	if($fecha_hoy <= $fechaSolicitada2)
	{
		$conexion 			= conectaBDSICLAB();
		$consulta 			= sprintf("select fechaAsignada,horaAsignada from lbcalendarizaciones
										where fechaAsignada=%s and horaAsignada=%s and PDOCVE=%s"
										,$fechaSolicitada,$horaSolicitada,$periodo);
		$res 	 			=  mysql_query($consulta);
		if($row = mysql_fetch_array($res))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}
//Se inserta la solicitud en la tabla lbcalendarizaciones
function guardaSolicitudLab()
{
	$respuesta 		= false;
	session_start();
		$periodo 		= periodoActual();
		$clave 			= GetSQLValueString($_POST["clave"],"int");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$hora 			= GetSQLValueString($_POST["hora"],"text");
		$firmaJefe 		= GetSQLValueString($_POST["firmaJefe"],"int");
		$comentarios	= GetSQLValueString($_POST["comentarios"],"text");
		$conexion 		= conectaBDSICLAB();
		if(existeSolLabG($clave) && consultaFechaSol($fecha,$hora,$periodo))
		{
			$consulta  		= sprintf("insert into lbcalendarizaciones values(%s,%d,%s,%s,%d,%s,%d,%s)",$periodo,'""',$fecha,$hora,$firmaJefe,$comentarios,$clave,'"NR"');
			$res 	 	=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
			$respuesta = true; 
		}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
//eliminar una solicitud de laboratorio
function eliminaSolicitudLab()
{
	$respuesta 		= false;
	session_start();
		$clave 			= GetSQLValueString($_POST["clave"],"int");
		$conexion 		= conectaBDSICLAB();
		if(existeSolLabG($clave))
		{
			$consulta  	= sprintf("update lbsolicitudlaboratorios set estatus='B' where claveSolicitud=%d",$clave);
			$res 	 	=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
			{
				$consulta2 = sprintf("update lbasignaarticulospracticas set estatus='B'where claveSolicitud=%d",$clave);
				$res2 	 	=  mysql_query($consulta2);
				if(mysql_affected_rows()>0)
				{
					$respuesta = true;
				}
			}
		}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
//funcion para sacar la lista de las solicitudes calendarizadas
function aceptadasLaboratorio()
{
	$respuesta 	= true;
	session_start();
		$con 				= 0;
		$rows				= array();
		$renglones			= "";
		$nombreMateria 		= "";
		$claveMateria 		= 0;
		$nombreMaestro 		= "";
		$claveMaestro 		= 0;
		$solAceptadasLab 	= "";
		$conexion 			= conectaBDSICLAB();
		$consulta			= sprintf("select s.claveSolicitud,s.MATCVE,p.tituloPractica,s.fechaSolicitud,s.horaSolicitud,s.claveUsuario 
								from lbusuarios as u 
								INNER JOIN lbsolicitudlaboratorios as s ON u.claveUsuario =s.claveUsuario 
								INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
								where s.estatus='V' and EXISTS (select * from lbcalendarizaciones as c where c.claveSolicitud=s.claveSolicitud and c.estatus='NR')");
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
			$claveMaestro 	= $rows[$c]["claveUsuario"];
			$nombreMaestro 	= nomMae($claveMaestro);
			$renglones .= "<td>".$nombreMaestro."</td>";
			$claveMateria 	= $rows[$c]["MATCVE"];
			$nombreMateria 	= nombreMat($claveMateria);
			$renglones .= "<td>".$nombreMateria."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas2'><i class='material-icons'>add</i></a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function verMas2()
{
	$respuesta 		= false;
	session_start();
		$claveCal 		= GetSQLValueString($_POST["clave"],"int");
		$renglones		= "";
		$fechaAsignada	= "";
		$horaAsignada	= "";
		$con 			= "";
		$maestro		= "";
		$cveMaestro		= 0;
		$practica 		= "";
		$nombreArticulo = "";
		$cantidad 		= "";
		$rows			= array();
		$conexion 		= conectaBDSICLAB();

		if(existeSolLabG($claveCal))
		{
			$consulta  		= sprintf("select a.claveCalendarizacion, a.fechaAsignada, a.horaAsignada, s.GPOCVE, b.nombreArticulo, d.cantidad, s.claveUsuario,c.tituloPractica
			from lbarticuloscat as b 
			INNER JOIN lbasignaarticulospracticas as d ON b.claveArticulo = d.claveArticulo
			INNER JOIN lbsolicitudlaboratorios as s ON d.claveSolicitud = s.claveSolicitud 
			INNER JOIN lbcalendarizaciones as a ON s.claveSolicitud = a.claveSolicitud 
			INNER JOIN lbpracticas as c ON s.clavePractica = c.clavePractica
			where s.claveSolicitud=%d ",$claveCal);
			$renglones	.= "<thead>";
			$renglones	.= "<tr>";
			$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
			$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
			$renglones	.= "</tr>";
			$renglones	.= "</thead>";
			$res 	 	=  mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{	
				$respuesta 		= true;
				$fechaAsignada 	= $row["fechaAsignada"];
				$horaAsignada 	= $row["horaAsignada"];
				$cveMaestro		= $row["claveUsuario"];
				$maestro 		= nomMae($cveMaestro);
				$practica 		= $row["tituloPractica"];
				$rows[] 		= $row;
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
	$arrayJSON = array('respuesta' => $respuesta, 
						'fechaAsignada' =>$fechaAsignada, 
						'horaAsignada' => $horaAsignada,
						'maestro' => $maestro, 
						'practica' => $practica, 
						'renglones' => $renglones);
		print json_encode($arrayJSON);
}
function jefeDepto($cvePersona)
{
	$clave 		= $cvePersona;
	$conexion 	= conectaBDSIE();
	$consulta 	= sprintf("select DEPCVE from DPUEST WHERE PERCVE =%s",$clave);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["DEPCVE"];
	}
	else
	{
		return 0;
	}
}
function listaArticulos()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$tipoUsu 	= tipoUsuario($responsable);
		$clavesLab 	= arrayLabs(jefeDepto(claveMaestro($responsable)));
		$conexion 	= conectaBDSICLAB();
		if ($tipoUsu == 5) 
		{
			$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
									FROM lbarticulos as A 
									INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
									INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.indentificadorArticulo 
									WHERE C.claveLaboratorio IN(%s) GROUP BY A.claveArticulo",$clavesLab);
			$res 		= mysql_query($consulta);
		}
		else
		{
			$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
									FROM lbarticulos as A 
									INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
									INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.indentificadorArticulo 
									WHERE C.claveLaboratorio =%s GROUP BY A.claveArticulo",$claveLab);
			$res 		= mysql_query($consulta);
		}
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
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
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function altaInventario1()
{
	$respuesta 	= false;
	session_start();
		$conexion					= conectaBDSICLAB();
		$imagen						= GetSQLValueString($_POST["imagen"],"text");
		$identificadorArticulo 		= "' '";
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
		$estatus 					= GetSQLValueString($_POST["estatus"],"text");
		$responsable 				= $_SESSION['nombre'];
		$claveLaboratorio 			= GetSQLValueString(obtieneCveLab($responsable),"text");
		//insert a tabla lbarticulos
		$consulta= sprintf("insert into lbarticulos values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			$claveArticulo,$descripcionUso,$descripcionArticulo,$numeroSerie,$marca,$modelo,$estatus,$unidadMedida,$fechaCaducidad,$tipoContenedor,$imagen,$identificadorArticulo,$ubicacionAsignada,$claveKit);
		$resconsulta = mysql_query($consulta);
		if(mysql_affected_rows()>0)
		{
			$identificadorArticulo = mysql_insert_id($conexion);
			$consulta2 	= sprintf("insert into lbasignaarticulos values(%d,%s)",$identificadorArticulo,$claveLaboratorio);
			$res 		= mysql_query($consulta2);
			if(mysql_affected_rows()>0)
			{
				$respuesta 	= true; 
			}
		}
	$salidaJSON = array('respuesta' => $respuesta,
						'idu' => $identificadorArticulo);
	print json_encode($salidaJSON);
	
}
function buscaArticulo()
{
	$respuesta 	= false;
	session_start();
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
			where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo);
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
		$responsable= $_SESSION['nombre'];
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
		$estatus				= GetSQLValueString($_POST["estatus"],"text");
		$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
		$conexion 	= conectaBDSICLAB();
		$consulta1	= sprintf("update lbarticulos set estatus='B' where identificadorArticulo=%d",$identificadorArticulo);
		$res = mysql_query($consulta1);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function arrayLabs($depto)
{
	$cveDep 	= $depto;
	$labs 		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveLaboratorio from lblaboratorios where DEPCVE=%d",$cveDep);
	$res 		= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$labs 	.= "'".($row["claveLaboratorio"])."',";
	}
	$labs = (rtrim($labs,","));
	return $labs;
}
function listaMantenimiento()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$tipoUsuario= tipoUsuario($responsable);
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		if($tipoUsuario = 5)
		{
			$depto 		= obtieneDepto($claveLab);
			$labs 		= arrayLabs($depto);
			$conexion 	= conectaBDSICLAB();
			$consulta	= sprintf("select ma.claveMovimiento,B.nombreArticulo, C.identificadorArticulo from lbarticuloscat B
				inner join lbarticulos as C ON B.claveArticulo=C.claveArticulo inner join lbmovimientosarticulos ma
				ON ma.identificadorArticulo=C.identificadorArticulo where ma.estatus='M' and ma.claveLaboratorio in(%s)",$labs);
			$res 		= mysql_query($consulta);
		}
		else
		{
			$conexion 	= conectaBDSICLAB();
			$consulta	= sprintf("select ma.claveMovimiento,B.nombreArticulo, C.identificadorArticulo from lbarticuloscat B
			inner join lbarticulos as C ON B.claveArticulo=C.claveArticulo inner join lbmovimientosarticulos ma
			ON ma.identificadorArticulo=C.identificadorArticulo where ma.estatus='M' and ma.claveLaboratorio=%s",$claveLab);
			$res 		= mysql_query($consulta2);
		}
		
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='codigoBarras'>Identificador artículo</th>";
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
			if($tipoUsuario != 3)
			{
				$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='btn-floating btn-large waves-effect  green darken-2' id='btnRegresaDelMtto'><i class='material-icons'>done</i></a></td>";
			}
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function mantenimientoArticulos()
{
	$respuesta = false;
	session_start();
		$responsable 			= $_SESSION['nombre'];
		$periodo				= periodoActual();
		$estatus				= '"M"';
		$claveMovimiento		= 0;
		$claveLab				= GetSQLValueString(obtieneCveLab($responsable),"text");
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
		$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
		$fechaMovimiento		= GetSQLValueString($_POST["fechaMovimiento"],"text");
		$horaMovimiento			= GetSQLValueString($_POST["horaMovimiento"],"text");
		$claveResponsable		= claveResp($responsable);
		$conexion 	= conectaBDSICLAB();

		$consulta 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%s,%s,%s,%s,%d,%s)",
						$periodo,$fechaMovimiento,$horaMovimiento,$claveResponsable,$identificadorArticulo,$observaciones,
						$estatus,$claveMovimiento,$claveLab);
		$res = mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function buscaArticuloMtto()
{
	$respuesta 	= false;
	session_start();
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
			where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo);
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
	$salidaJSON = array('respuesta' 	=> $respuesta,
						'modelo' 		=> $modelo, 
						'numeroSerie' 	=> $numeroSerie, 
						'nombreArticulo'=> $nombreArticulo,
						'marca' 		=> $marca, 
						'fechaCaducidad'=> $fechaCaducidad);
	print json_encode($salidaJSON);
}
function tipoUsuario($claveUsuario)
{
	$cve 			= $claveUsuario;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select tipoUsuario from lbusuarios where claveUsuario=%d",$cve);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)$row["tipoUsuario"];
	}
	else
	{
		return 0;
	}
}
function peticionesPendientesArt()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$labCve 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$depto 		= jefeDepto(claveMaestro($responsable));
		$labs 		= arrayLabs($depto);
		$tipoUsuario= tipoUsuario($responsable);
		$claveResp 	= claveResp($responsable);
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$nombreLaboratorio = "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsuario = 5)
		{
			$consulta	= sprintf("select clavePedido,claveLaboratorio,nombreArticulo,cantidad 
								from lbpedidos 
								where estatus='P' and claveLaboratorio IN(%s)",$labs);
		}
		else
		{
			$consulta	= sprintf("select p.clavePedido,p.claveLaboratorio,p.nombreArticulo,p.cantidad 
								from lbpedidos p 
								where estatus='P' and claveResponsable=%s",$claveResp);	
		}
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
			$nombreLaboratorio = nombreLab($rows[$c]["claveLaboratorio"]);
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$nombreLaboratorio."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td><a name ='".$rows[$c]["clavePedido"]."'class='btn-floating btn-large waves-effect green darken-2' id='btnAceptaPeticionArt'><i class='material-icons'>done</i></a></td>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$arrayJSON = array('respuesta' 	=> $respuesta,
						'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function nombreLab($claveLab)
{
	$cve 			= GetSQLValueString($claveLab,"text");
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select nombreLaboratorio from lblaboratorios where claveLaboratorio=%s",$cve);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["nombreLaboratorio"];
	}
	else
	{
		return 0;
	}
}
function aceptaPeticionArticulos()
{
	$respuesta 	= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$clavePedido 	= GetSQLValueString($_POST["clavePedido"],"text");
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("update lbpedidos set estatus='A' where clavePedido=%s",$clavePedido);
		$res 			= mysql_query($consulta);
		if(mysql_affected_rows()>0)
				$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function prestamosPendientes()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$nombreAlu 	= "";
		$tipoUsuario= tipoUsuario($responsable);
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
			$nombreAlu 	= consultaAlumno($rows[$c]["ALUCTR"]); 
			$renglones .= "<td>".$nombreAlu."</td>";
			$renglones .= "<td>".$rows[$c]["fechaEntrada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaEntrada"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light  green darken-2' id='btnAtenderPrestamo'>Atender</a></td>";
			if($tipoUsuario != 3)
			{
				$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light red darken-1 eliminarPrestamo' id='btnEliminarPrestamo' type='submit'>Eliminar</a></td>";
			}
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$salidaJSON = array('respuesta' => $respuesta, 
						'renglones' => $renglones,
						'nombre' 	=> $nombreAlu);
	print json_encode($salidaJSON);
}
function atenderPrestamo()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$clavePrestamo = GetSQLValueString($_POST["clavePrestamo"],"int");
		$con 		= 0;
		$fechaActual= date("d-m-Y");
		$rows		= array();
		$renglones	= "";
		$nombre		= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select p.ALUCTR,p.clavePrestamo,ac.claveArticulo,ac.nombreArticulo,sa.cantidad 
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
	$salidaJSON = array('respuesta' => $respuesta,
						 'renglones' => $renglones, 
						 'clavePrestamo' => $clavePrestamo);
	print json_encode($salidaJSON);
}
function agregaArticulos()
{
	$respuesta 	= false;
	session_start();
		$responsable			= $_SESSION['nombre'];
		$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
		$clavePrestamo			= GetSQLValueString($_POST["clavePrestamo"],"int");
		$claveLaboratorio 		= GetSQLValueString(obtieneCveLab($responsable),"text");
		$idu 					= 0;
		$nomArt 				= "";
		if (consultaArtLab($identificadorArticulo,$claveLab)) 
		{
			if (buscaArtSolicitud($clavePrestamo,$identificadorArticulo)) {
				$conexion 				= conectaBDSICLAB();
				$consulta				= sprintf("select A.identificadorArticulo,B.nombreArticulo,B.claveArticulo
													from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
													where A.estatus='V' and A.identificadorArticulo=%d",$identificadorArticulo);
				$res 					= mysql_query($consulta);
				if($row = mysql_fetch_array($res))
				{
					$idu  		=$row["identificadorArticulo"];
					$nomArt 	=$row["nombreArticulo"];
					$respuesta 	= true;
				}
			}
		}
	$salidaJSON = array('respuesta' => $respuesta,
						 'idu' 		=> $idu,
						 'nomArt' 	=> $nomArt);
	print json_encode($salidaJSON);
}
function consultaArtLab($idu,$lab)
{
	$identificador 	= $idu;
	$cveLab 		= $lab;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select identificadoartrArticulo 
								from lbasignaarticulos 
								where claveLaboratorio=%s and identificadorArticulo=%d",$cveLab,$identificador);
	$res 			= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function buscaArtSolicitud($prestamo,$idu)
{
	$identificador 	= $idu;
	$cvePrestamo 	= $prestamo;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select sa.claveArticulo from lbsolicitudarticulos sa
								inner join lbarticulos a on sa.claveArticulo=a.claveArticulo
								where a.identificadorArticulo=%d and sa.clavePrestamo=%d",$identificador,$cvePrestamo);
	$res 			= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}	
}
 function guardaPrestamoPendiente()
 {
 	$respuesta 	= false;
	session_start();
		$responsable	= $_SESSION['nombre'];
		$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"int");
		$listaArt		= $_POST['listaArt'];
		$arrayArt 		= explode(',',$listaArt); 
		$cantidad 		= count($arrayArt);
		$claveResponsable= claveResp($responsable);
		for ($i=0; $i < $cantidad ; $i++) 
		{ 
			$conexion 	= conectaBDSICLAB();
			$consulta	= sprintf("insert into lbprestamosarticulos values(%s,%s,%d,%s)",'""',$arrayArt[$i],$clavePrestamo,'"P"');
			$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
			{
				if(actualizaSolArt($clavePrestamo))
				{
					$consulta2 = sprintf("update lbprestamos set claveResponsable=%d where clavePrestamo=%d",$claveResponsable,$clavePrestamo);
					$res 		= mysql_query($consulta);
					if(mysql_affected_rows()>0)
					{
						$respuesta = true;
					}
				}
			}
		}
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
 }
 function actualizaSolArt($prestamo)
 {
 	$cveP 		= $prestamo;
 	$conexion 	= conectaBDSICLAB();
 	$consulta	= sprintf("update lbsolicitudarticulos set estatus ='A' where clavePrestamo=%d",$cveP);
	$res 		= mysql_query($consulta);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
 }
function eliminaPrestamoPendiente()
{
	$respuesta 	= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"text");
		$conexion 		= conectaBDSICLAB();
		$consulta	= sprintf("update lbsolicitudarticulos set estatus='N' 
								where clavePrestamo=%s",$clavePrestamo);
		$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function prestamosProceso()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$nombreAlu 	= "";
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
			$nombreAlu 	= consultaAlumno($rows[$c]["ALUCTR"]);
			$renglones .= "<td>".$nombreAlu."</td>";
			$renglones .= "<td>".$rows[$c]["fechaPrestamo"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaPrestamo"]."</td>";
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='waves-effect waves-light btn amber darken-2' id='btnDevolucionMaterial'>Devolución</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function consultaAlumno($nc)
{
	$respuesta		= false;
	$nControl		= $nc;
	$ALUAPP 		= "";
	$ALUAPM			= "";
	$ALUNOM			= "";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select ALUAPP, ALUAPM, ALUNOM from DALUMN where ALUCTR=%s limit 1",$nControl);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["ALUAPP"]." ".$row["ALUAPM"]." ".$row["ALUNOM"];
	}

}
function devolucionPrestamo()
{
	$respuesta 	= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$prestamo		= "";
		$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
		$numC 			= consultaNCPrestamo($clavePrestamo);
		$nombreAlu 		= consultaAlumno($numC);
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
			$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='waves-effect waves-light btn amber darken-2 aplicaSancion' id='btnAplicaSancion'>Sancionar</a></td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$salidaJSON = array('respuesta' => $respuesta, 
						'renglones' => $renglones, 
						'clavePrestamo' => $clavePrestamo,
						'numeroControl' => $numC,
						'nombreAlumno' => $nombreAlu);
	print json_encode($salidaJSON);
}
function consultaNCPrestamo($cveP)
{
	$clave 		= $cveP;
	$nc 		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta  	= sprintf("select ALUCTR from lbprestamos where clavePrestamo=%d",$clave);
	$res 	 	=  mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["ALUCTR"]);
	}
	else
	{
		return 0;
	}
}
function guardaDevolucion()
{
	$respuesta 	= false;
	session_start();
		$responsable			= $_SESSION['nombre'];
		$clavePrestamo 			= GetSQLValueString($_POST["clavePrestamo"],"text");
		$identificadorArticulo 	= GetSQLValueString($_POST["identificadorArticulo"],"text");
		$fecha 					= GetSQLValueString($_POST["fechaDevolucion"],"text");
		$hora 					= GetSQLValueString($_POST["horaDevolucion"],"text");
		$periodo 				= periodoActual();
		$claveResponsable 		= claveResp($responsable);
		$conexion 				= conectaBDSICLAB();
		$consulta  				= sprintf("insert into lbdevoluciones values(%s,%s,%s,%s,%s,%s)",
									$periodo,$clavePrestamo,$identificadorArticulo,$claveResponsable,$fecha,$hora);
		$res 	 				=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true; 
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function aplicaSancion()
{
	$respuesta 	= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$idArt 			= GetSQLValueString($_POST["identificador"],"text");
		$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
		$numeroControl 	= GetSQLValueString($_POST["nc"],"text");
		$nombre 		= GetSQLValueString($_POST["nom"],"text");
		$con 			= 0;
		$comboSanciones	= array();
		$claveSancion	= "";
		$nombreSancion	= "";
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select claveSancion,nombreSancion 
								from lbsanciones");
		$res 		= mysql_query($consulta);
		if($res)
		{
			while($row = mysql_fetch_array($res))
			{
				$comboSanciones[] = $row;
				$respuesta = true;
				$con++;
			}
			for ($i=0; $i < $con ; $i++)
			{ 
				$claveSancion[] 	=$comboSanciones[$i]["claveSancion"];
				$nombreSancion[] 	=$comboSanciones[$i]["nombreSancion"];
			}
		}
	$arrayJSON = array('respuesta' 		=> $respuesta,
						'claveSancion' 	=> $claveSancion, 
						'nombreSancion' => $nombreSancion, 
						'contador' 		=> $con,
						'prestamo' 		=> $clavePrestamo);
	print json_encode($arrayJSON);
}
function guardaSancion()
{
	$respuesta 	= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$periodo 		= periodoActual();
		$idArt 			= GetSQLValueString($_POST["idu"],"text");
		$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
		$numControl 	= GetSQLValueString($_POST["nc"],"text");
		$claveSancion 	= GetSQLValueString($_POST["claveSancion"],"int");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$comentario 	= GetSQLValueString($_POST["comentario"],"text");
		$cveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("insert into lbasignasanciones values(%s,%d,%d,%s,%s,%s,%s,%s,%s,%s)",
							$periodo,'""',$claveSancion,$numControl,$fecha,'"dd/mm/aaaa"',$comentario,$idArt,$cveLab,'"P"');
		$res 	 		=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
			{
				$consulta2 = sprintf("update lbprestamosarticulos set estatus='S' where identificadorArticulo=%s and clavePrestamo=%d",$idArt,$clavePrestamo);
				$res 	 		=  mysql_query($consulta2);
				if(mysql_affected_rows()>0)
				{
					$respuesta = true;
				}
			}	
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function listaAlumnosSancionados()
{
	$respuesta 	= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$tipoUsuario= tipoUsuario($responsable);
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select sa.ALUCTR,sa.inicioSancion,sa.finSancion,sa.comentarios,s.claveSancion,ac.nombreArticulo 
							from lbsanciones s 
							inner join lbasignasanciones sa ON sa.claveSancion=s.claveSancion 
							INNER JOIN lbarticulos art ON art.identificadorArticulo=sa.identificadorArticulo 
							INNER JOIN lbarticuloscat ac ON ac.claveArticulo=art.claveArticulo
							where sa.estatus='P'");
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
			if($tipoUsuario != 3)
			{
				$renglones .= "<td><a name = '".$rows[$c]["claveSancion"]."' class='btn waves-effect waves-light green darken-2' id='btnQuitaSancion'>Quitar</a></td>";
			}
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function quitaSanciones()
{
	$respuesta 		= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$claveSancion 	= GetSQLValueString($_POST["claveSancion"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("update lbasignasanciones set estatus='L', finSancion=%s where claveSancion=%d",$fecha,$claveSancion);
	$res 	 		=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	
	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function guardaPeticionArticulos()
{
	$respuesta 		= false;
	session_start();
		$responsable	= $_SESSION['nombre'];
		$nombreArticulo = GetSQLValueString($_POST["nombreArticulo"],"text");
		$cantidad		= GetSQLValueString($_POST["cantidad"],"text");
		$marca 			= GetSQLValueString($_POST["marca"],"text");
		$modelo 		= GetSQLValueString($_POST["modelo"],"text");
		$motivo 		= GetSQLValueString($_POST["motivo"],"text");
		$fecha 			= GetSQLValueString($_POST["fecha"],"text");
		$cveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
		$firma 			= "0000";
		$cveResp 		= claveResp($responsable);
		$periodo 		= periodoActual();
		$conexion 		= conectaBDSICLAB();
		$consulta  		= sprintf("insert into lbpedidos values(%s,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s)",$periodo,'""',$fecha,$cveLab,$firma,$cveResp,$nombreArticulo,$cantidad,$motivo,$marca,$modelo,'"P"');
		$res 	 		=  mysql_query($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true; 

	$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
}
function obtieneCveLab($clave)
{
	$cveResp 		= $clave;
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveLaboratorio from lbresponsables where claveUsuario=%d",$cveResp);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveLaboratorio"];
	}
	else
	{
		return 0;
	}
}
function obtieneDepto($claveLab)
{
	$cveLab 		= $claveLab;
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select DEPCVE 
								from lblaboratorios 
								where claveLaboratorio=%s",$cveLab);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["DEPCVE"];
	}
	else
	{
		return 0;
	}

}
function listaArticulosAlta()
{
	$resp 	 		= false;
	session_start();
		$responsable 	= $_SESSION['nombre'];
		$claveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
		$departamento 	= obtieneDepto($claveLab);
		if ($departamento == 420) 
		{
			$flag="'S'";
		}
		elseif ($departamento == 407) 
		{
			$flag="'B'";
		}
		elseif ($departamento == ( 404 || 406 || 408 || 410)) 
		{
			$flag="'M'";
		}
		else
		{
			$flag="'G'";
		}

		$contador		= 0;
		$comboMaterial	= array();
		$claveMaterial 	= "";
		$nombreMaterial = "";
		$conexion		= conectaBDSICLAB();
		$consulta 		= sprintf("select claveArticulo,nombreArticulo 
									from lbarticuloscat where bandera=%s",$flag);
		$res 			= mysql_query($consulta);
		while($row = mysql_fetch_array($res))
			{
				$comboMaterial[] 	= $row;
				$resp 		 		= true;
				$contador++;
			}
			for ($i=0; $i < $contador ; $i++)
			{ 
				$claveMaterial[] 	=$comboMaterial[$i]["claveArticulo"];
				$nombreMaterial[] 	=$comboMaterial[$i]["nombreArticulo"];
			}
	$arrayJSON = array('respuesta' => $resp,
						'claveMaterial' => $claveMaterial,
						'nombreMaterial' => $nombreMaterial, 
						'contador' => $contador);
	print json_encode($arrayJSON);
}
function comboArtPeticiones()
{
	$resp 	 		= false;
	session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$art 		= "";
		$articulos 	= "";
		$con 		= 0;
		$comboArt	= array();
		$conexion 	= conectaBDSICLAB();
		$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo
					FROM lbarticulos as A inner join lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
					INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.indentificadorArticulo 
					WHERE C.claveLaboratorio =%s GROUP BY A.claveArticulo",$claveLab);
		$res 		= mysql_query($consulta);
		if($res)
		{
			while($row = mysql_fetch_array($res))
			{
				$comboArt[] = $row;
				$respuesta = true;
				$con++;
			}
			for ($i=0; $i < $con ; $i++)
			{ 
				$articulos[] 	=$comboArt[$i]["nombreArticulo"];
			}
		}
	$arrayJSON = array('respuesta' => $respuesta, 
						'nombreArt' => $articulos, 
						'contador' => $con);
	print json_encode($arrayJSON);
}
function regresaMantenimiento()
{
	$respuesta 	 		= false;
	session_start();
		$responsable 			= $_SESSION['nombre'];
		$identificador 			= GetSQLValueString($_POST["iduArt"],"int");
		$periodo				= periodoActual();
		$estatus				= '"E"';
		$claveMovimiento		= 0;
		$claveLab				= GetSQLValueString(obtieneCveLab($responsable),"text");
		$observaciones			= "Regresa de mantenimiento";
		$fechaMovimiento		= GetSQLValueString($_POST["fecha"],"text");
		$horaMovimiento			= GetSQLValueString($_POST["hora"],"text");
		$claveResponsable 		= claveResp($responsable);
		$conexion 	= conectaBDSICLAB();

		$consulta 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%s,%s,%s,%s,%d,%s)",
						$periodo,$fechaMovimiento,$horaMovimiento,$claveResponsable,$identificador,$observaciones,
						$estatus,$claveMovimiento,$claveLab);
		$res = mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
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
	case 'aceptaPeticionArticulos1':
	aceptaPeticionArticulos();
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
	salirG();
	break;
	case 'guardaPeticionArticulos1':
	guardaPeticionArticulos();
	break;
	case 'listaArtAlta':
	listaArticulosAlta();
	break;
	case 'comboArtPeticiones1':
	comboArtPeticiones();
	break;
	case 'regresaMtto1':
	regresaMantenimiento();
	break;
} 
?>