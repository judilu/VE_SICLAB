<div id="datosPractica2">
	<?php require_once('../data/conexion.php');?>
	<!--<?php //require_once('../data/alumnos.php');?>-->
	<h5>Datos de la práctica</h5>
	<div class="row">
		<div class="col s12">
			<div class="row cMat">
				<div class="input-field col s5">
					<select id="cmbMateriasAlumnos">
						<option value="" disabled selected>Selecciona la materia</option>
					</select>
					<label>Nombre de la materia</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select id="cmbMaestrosMat">
						<option value="" disabled selected>Selecciona el maestro</option>
					</select>
					<label>Nombre del maestro</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s5">
					<select id="cmbNombrePracticas">
						<option value="" disabled selected>Selecciona la práctica</option>
					</select>
					<label>Nombre de la práctica</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select id="cmbHorariosPractica">
						<option value="" disabled selected>Selecciona la hora</option>
					</select>
					<label>Hora de la práctica</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<a class="btn waves-effect waves-light amber darken-2" type="submit" name="action" id="btnMaterialAlumno">Elegir material</a>
					<a class="btn waves-effect waves-light green darken-2" type="submit" name="action" id="btnEntrar">Entrar</a>
				</div>
			</div>
		</div>
	</div>  
</div>
<div id="materialAlumno">
	<?php include 'eleccionMaterialAlumno.php';?>
</div> 