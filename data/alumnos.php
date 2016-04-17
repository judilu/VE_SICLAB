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
function consultaAlumno()
{
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
									WHERE ap.estatus = 'V' and ap.claveSolicitud=%s",$claveCal);
			$res 			= mysql_query($consulta);
			while($row = mysql_fetch_array($res))
			{
				$materiales .="'".($row["claveArticulo"])."',";
				$cantidad 	.="'".($row["cantidad"])."',";
				$nombre 	.="'".($row["nombreArticulo"])."',";
				$rows[]=$row;
				$respuesta = true;
				$con++;
			}
			$materiales = (rtrim($materiales,","));
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
function agregarArtAlumno()
{
	$cveArt 	= GetSQLValueString($_POST['artCve'],"text");
	$num 	 	= GetSQLValueString($_POST['numArt'],"int");
	$nomArt 	= $_POST['artNom'];
	$respuesta	= true;
	$renglones	= "";
	$renglones .= "<tr id=".$cveArt.">";
	$renglones .= "<td class='col s6'>".$nomArt."</td>";
	$renglones .= "<td class='col s3'>".$num."</td>";
	$renglones .= "<td class='col s3'><a name =".$cveArt."class='btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1'><i class='material-icons'>delete</i></a></td>";
	$renglones .= "</tr>";
	$arrayJSON = array('respuesta' => $respuesta,
						'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function materialesDisponibles()
{
	$claveCal 		= GetSQLValueString($_POST['claveCal'],"int");
	$resp 	 		= false;
	$contador		= 0;
	$laboratorio 	= "'CCDM'";
	$comboArticulos	= array();
	$claveArt 		= "";
	$nombreArt		= "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select a.claveArticulo,ac.nombreArticulo 
						from lbasignaarticulos aa 
						INNER JOIN lbarticulos a on aa.indentificadorArticulo=a.identificadorArticulo 
						INNER JOIN lbarticuloscat ac on a.claveArticulo=ac.claveArticulo 
						LEFT JOIN (
									select * from lbasignaarticulospracticas aap 
									where aap.claveSolicitud=(
										SELECT c.claveSolicitud 
										from lbcalendarizaciones c 
										where c.claveCalendarizacion=%d)) as t 
						on a.claveArticulo=t.claveArticulo  
						where aa.claveLaboratorio=%s and t.claveSolicitud IS NULL",$claveCal,$laboratorio);
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
	$arrayJSON = array('respuesta' => $resp,'claveArticulo' => $claveArt,'nombreArticulo' => $nombreArt, 
						'contador' => $contador);
	print json_encode($arrayJSON);

}
function consultaClavePrestamo($clave,$numCtrl)
{
		$responsable	= "6";
		$periodo 		= periodoActual();
		$conexion 		= conectaBDSICLAB();
		$query  		= sprintf("select clavePrestamo 
						from lbprestamos 
						where claveCal=%s and ALUCTR=%s and PDOCVE=%s ",$periodo,$clave,$numCtrl);
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
			$consulta	= sprintf(" insert into lbsolicitudarticulos values(%s,%s,%s,%s,%s)",'""',
									$arrayArt[$i],$clavePrestamo,$arrayCant[$i],'"S"');
			$res 		= mysql_query($consulta);
			var_dump($consulta);
			if(mysql_affected_rows()>0)
				$respuesta = true;
		}
		$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
		
}
function construirTablaArt()
{
	$cveArt[] 	= $_POST['articulosSolicitados'];
	$nomArt[] 	= $_POST['nombreArticulos'];
	$num[] 		= $_POST['numeroArticulos'];
	$n 			= count($cveArt);
	$respuesta 	= false;
	$renglones 	= "";
	for ($i=0; $i < $n ; $i++) 
	{ 
		if ($cveArt[$i]!= "") 
		{
			$respuesta	= true;
			$renglones	= "";
			$renglones .= "<td class='col s6'>".$num[$i]."</td>";
			$renglones .= "<td class='col s3'>".$nomArt[$i]."</td>";
			$renglones .= "<td class='col s3'><a name ='".$cveArt[$i]."' class='btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1'><i class='material-icons'>delete</i></a></td>";
			$renglones .= "</tr>";
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'renglones' => $renglones);
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
	case 'guardaEntrada1':
		guardaEntrada();
	break;
	case 'consultaNomAlumno':
		consultaAlumno();
	break;
	case 'consultaMaterialPractica1':
		consultaMaterialPractica();
	break;
	case 'agregarArtAlu1':
		agregarArtAlumno();
	break;
	case 'materialesDisponibles1':
		materialesDisponibles();
	break;
	case 'guardaSolicitudAlu':
		guardaSolicitudAlumno();
	break;
	case 'construirTablaArt1':
		construirTablaArt();
	break;
} 
?>