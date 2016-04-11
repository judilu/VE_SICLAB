<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="stylesheet" href="../css/genericos.css">
	<script src="../js/usuarios.js"></script>
	<script src="../js/genericos.js"></script>
</head>
<body>
	<div id="pantallaLaboratorios" class="body2">
		<div class="row">
			<div class="col s12">
				<ul class="tabs" id="selector">
					<li id="tabPrestamos" class="tab col s2"><a class="active breadcrumb" href="#prestamos">Préstamos</a></li>
					<li id="tabLabs" class="tab col s2"><a class="breadcrumb" href="#laboratorios">Laboratorios</a></li>
					<li id="tabInventario" class="tab col s2"><a class="breadcrumb" href="#inventarios">Inventario</a></li>
					<li id="tabReportesGenericos" class="tab col s2"><a class="breadcrumb" href="#reportesGenericos">Reportes</a></li>
					<li id="tabSalir"class="tab col s3"><a class="breadcrumb" href="#salir">Salir</a></li>
				</ul>
			</div>
		</div>
		<div id="prestamos" class="col s12">
			<div id="menuPrestamos">
				<ul>
					<a class="waves-effect waves-light btn blue darken-1" id="btnPendientes">Pendientes</a>
					<a class="waves-effect waves-light btn blue darken-2" id="btnEnProceso">En proceso</a>
					<a class="waves-effect waves-light btn blue darken-2" id="btnListaSanciones">Sanciones</a>
				</ul>
			</div>
			<div id="solicitudesPendientes">
				<?php include 'solicitudesPendientes.php';?>
			</div>
			<div id="solicitudesEnProceso">
				<?php include 'solicitudesEnProceso.php';?>
			</div>
			<div id="alumnosSancionados">
				<?php include 'listaSanciones.php';?>
			</div>
		</div>
		<div id="laboratorios" class="col s12">
			<div id="menuLaboratorios">
				<ul>
					<a class="waves-effect waves-light btn blue darken-1" id="btnPendientesLab">Pendientes</a>
					<a class="waves-effect waves-light btn grey" id="btnAceptadasLab">Aceptadas</a>
				</ul>
			</div>
			<div id="sPendientesLab">
				<?php include 'solicitudesPendientesLaboratorio.php';?>
			</div>
			<div id="sAceptadasLab">
				<?php include 'solicitudesAceptadasLaboratorio.php';?>
			</div>
		</div>
		<div id="inventarios">
			<div id="menuInventario">
				<ul>
					<a class="waves-effect waves-light btn grey" id="btnArticulos">Articulos</a>
					<a class="waves-effect waves-light btn blue darken-2" id="btnAlta">Alta</a>
					<a class="waves-effect waves-light btn blue darken-2" id="btnBaja">Baja</a>
					<a class="waves-effect waves-light btn blue darken-2" id="btnMantenimiento">Mantenimiento</a>
					<a class="waves-effect waves-light btn blue darken-1" id="btnPeticionesPendientes">Peticiones pendientes</a>
					<a class="waves-effect waves-light btn blue darken-1" id="btnPeticionArticulo">Petición artículo</a>
				</ul>
			</div>
			<div id="pantallaInventario">
				<h5>Inventario</h5>
				<?php include 'pantallaInventario.php';?>
			</div>
			<div id="altaArticulos">
				<h5>Alta de artículos</h5>
				<?php include 'altaArticulos.php';?>
			</div>
			<div id="bajaArticulos">
				<h5>Baja de artículos</h5>
				<?php include 'bajaArticulos.php';?>
			</div>
			<div id="mantenimientoArticulos">
				<?php include 'menuMantenimiento.php';?>
			</div>
			<div id="peticionesPendientes">
				<?php include 'peticionesPendientes.php';?>
			</div>
			<div id="peticionesArticulos">
				<h5>Peticiones de articulos</h5>
				<?php include 'peticionesArticulos.php';?>
			</div>
		</div>
		<div id="reportesGenericos">
			<div id="menuReportes">
				<ul>
					<a class="waves-effect waves-light btn blue darken-2" id="btnResumenReportes">Resumen</a>
					<!-- Dropdown Trigger -->
					  <a class='dropdown-button btn waves-effect waves-light blue darken-2' data-activates='dropdown1'>Inventarios</a>

					  <!-- Dropdown Structure -->
					  <ul id='dropdown1' class='dropdown-content'>
					    <li><a id="btnExistenciaInventario">Existencias</a></li>
					    <li><a id="btnBajoInventario">Bajo inventario</a></li>
					    <li><a id="btnMaterialDañado">En reparación</a></li>
					    <li><a id="btnMaterialEnPrestamo">En prestamo</a></li>
					    <li><a id="btnPedidoMaterial">Pedidos</a></li>
					  </ul>
				</ul>
			</div>
			<div id="resumenReportes">
				<h5>Resumen</h5>
				<?php include 'resumenReportes.php';?>
			</div>
			<div id="bajoInventario">
				<?php include 'bajoInventario.php';?>
			</div>
			<div id="existenciaInventario">
				<?php include 'existenciaInventario.php';?>
			</div>
			<div id="pedidoMaterial">
				<?php include 'pedidoMaterial.php';?>
			</div>
		</div>
		<div id="salir">
			<?php include 'salirSistema.php';?>
		</div>
	</div>
</body>
</html>