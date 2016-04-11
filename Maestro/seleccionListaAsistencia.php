<div id="listaAsistencia">
	<div id="selecionarLista">
		<div class="row">
			<div class="col s12">
				<div class="row">
					<div class="input-field col s3">
						<input id="txtClaveMaestro" type="text" class="validate">
						<label for="txtClaveMaestro">Clave</label>
					</div>
					<div class="input-field col s5 offset-s3">
						<input id="txtNombreMaestro" type="text" class="validate">
						<label for="txtNombreMaestro">Nombre</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field col s7" id="selectMatReporte">
					<select>
						<option value="" disabled selected>Seleccione La materia</option>
						<option value="1">Option 1</option>
						<option value="2">Option 2</option>
						<option value="3">Option 3</option>
					</select>
					<label>Materia</label>
				</div>
				<div class="input-field col s3 offset-s1">
					<select>
						<option value="" disabled selected>Seleccione la hora</option>
						<option value="1">12:30</option>
						<option value="2">01:00</option>
						<option value="3">15:00</option>
					</select>
					<label>Hora de la Materia</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6" id="selectPractica">
						<select>
							<option value="" disabled selected>Seleccione la práctica</option>
							<option value="1">Option 1</option>
							<option value="2">Option 2</option>
							<option value="3">Option 3</option>
						</select>
						<label>Práctica</label>
					</div>
					<div class="input-field col s3">
						<input type="date" class="datepicker">
						<label for="txtFecha"></label>
					</div>
					<div class="input-field col s2">
						<select>
							<option value="" disabled selected>Seleccione la hora</option>
							<option value="1">12:30</option>
							<option value="2">01:00</option>
							<option value="3">15:00</option>
						</select>
						<label>Hora de la Práctica</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="row">
					<div class="col s6 offset-s6">
						<a id="btnListaAsistencia" class="waves-effect waves-light btn green darken-2"><i class="material-icons left">done</i>Lista de asistencia</a>
						<a id="btnCancelar" class="waves-effect red darken-1 btn"><i class="material-icons left">clear</i>Cancelar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="lista">
		<?php include 'listaAsistencia.php';?>
	</div>
</div>