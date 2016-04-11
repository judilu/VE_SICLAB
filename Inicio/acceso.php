<!DOCTYPE html>
<html lang="es">
<head>
	<?php include 'head.php';?>
	<?php include 'encabezado.php';?>
	<script src="../js/usuarios.js"></script>
</head>
<body>
	<form id="acceso" class="body2">
		<div class="row">
			<div class="col s6 offset-s3 card-panel white">
				<div class="row">
					<div class="col s8 offset-s2"> 
						<h5>Ingrese sus datos de inicio:</h5>
					</div>
					<div class="col s12">
						<div class="input-field col s10 offset-s1">
				          <i class="material-icons prefix">account_circle</i>
				          <input id="txtUsuario" type="text" class="validate" autofocus>
				          <label for="txtUsuario">Usuario</label>
				        </div>
					</div>
					<div class="col s12">
						<div class="input-field col s10 offset-s1">
				          <i class="material-icons prefix">lock</i>
				          <input id="txtClave" type="password" class="validate">
				          <label for="txtClave">Contraseña</label>
				        </div>
					</div>
					<div class="col s12">
						<div class="col s6 offset-s4">
							<a class="waves-effect waves-light btn blue darken-1" id="btnIngresar"><i class="material-icons right">perm_identity</i>Ingresar</a>
						</div>
					</div>
				</div>
	        </div>
	    </div>
	</form>
	<div id="maestro">
		<?php include '../Maestro/maestro.php';?>
	</div>
	<div id="alumno">
		<?php include '../Alumno/accesoAlumno.php';?>
	</div>
	<div id="genericos">
		<?php include '../Genericos/pantallaLaboratorios.php';?>
	</div>
</body>
<footer>
		<?php include 'footer.php';?>
</footer>
</html>