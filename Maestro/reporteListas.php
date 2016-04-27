<!DOCTYPE html>
<html lang="es">
<head>
	<meta  http-equiv="Content-type" content="text/html;charset='UTF-8'">
	<title>SICLAB</title>
	<link rel="stylesheet" href="../css/materialize/css/materialize.css">
	<link rel="stylesheet" href="../css/siclab.css">
	<link rel="stylesheet" href="../css/maestro.css">
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../sweetalert-master/dist/sweetalert.css">
	<script src="../js/jquery-2.2.0.js"></script>
	<script src="../js/materialize.min.js"></script>
	<script src="../sweetalert-master/dist/sweetalert.min.js"></script>
	<script src="../js/maestros.js"></script>
</head>
<body>
	<header>
		<div class="row">
			<div class="col">
				<img id="LogoTec" src="../img/logo.png" alt="">
			</div>
			<div class="col">
				<div class="row">
					<h5>Nombre del documentos: Formato</h5>
					<h5>Control de asistencia de práctica de</h5>
					<h5>laboratorio realizada</h5>
				</div>
				<div class="row">
					<h5>Referencia a la norma ISO</h5>
				</div>
			</div>
			<div class="col">
				<div class="row">
					<h5>Código: XXX-XX-XX</h5>
				</div>
				<div class="row">
					<h5>Revisión:1</h5>
				</div>
				<div class="row">
					Página 1 de x
				</div>
			</div>
		</div>
	</header>
	<div class="listaAsistencia">
		<h5>INSTITUTO TECNOLOGICO DE CULIACAN</h5>
		<h5>Departamento:</h5><h5 id="lbNomDepto">Metal-Mecanica</h5>
		<h5 id="lbNomLab">Nombre del laboratorio</h5>
		<div class="row">
			<div class="col s12">
				<div class="row">
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtClaveMaestroRep2" type="text" class="validate">
						<label class="active"for="txtClaveMaestroRep2">Clave</label>
					</div>
					<div class="input-field col s7">
						<input disabled placeholder="" id="txtNombreMaestroRep2" type="text" class="validate">
						<label class="active" for="txtNombreMaestroRep2">Nombre</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id="txtPeriodoRep" type="text" class="validate">
						<label class="active" for="txtPeriodoRep">Periodo</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
					<input disabled placeholder="" id="txtCarreraRep" type="text" class="validate">
					<label class="active" for="txtCarreraRep">Carrera</label>
				</div>
				<div class="input-field col s4">
					<input disabled placeholder="" id="txtMateriaRep" type="text" class="validate">
					<label class="active" for="txtMateriaRep">Materia</label>
				</div>
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtHoraMatRep" type="text" class="validate">
						<label class="active" for="txtHoraMatRep">Hora de la materia</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s7">
						<input disabled placeholder="" id="txtPracticaRep" type="text" class="validate">
						<label class="active" for="txtPracticaRep">Práctica</label>
					</div>
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtHoraPractRep" type="text" class="validate">
						<label class="active" for="txtHoraPractRep">Hora de la práctica</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id= "txtFechaPracticaRep2" type="text" class="validate">
						<label class="active" for="txtFechaPracticaRep2">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="col s10 offset-s1">
						<table  id ="tbListaAsistencia" class="bordered responsive-table">

						</table>
					</div>
				</div>
			</div>  
		</div>
		<div class="row">
			<div class="col s5 offset-s7">
				<a id="btnImprimir" class="waves-effect waves-light btn green darken-2 "><i class="material-icons left">print</i>Imprimir</a>
			</div>
		</div>
	</div>	
</body>
</html>