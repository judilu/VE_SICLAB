<div id="datosPractica2">
	<h5>Datos de la práctica</h5>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s5">
					<select>
						<option value="" disabled selected>Selecciona el maestro</option>
						<option value="1">Option 1</option>
						<option value="2">Option 2</option>
						<option value="3">Option 3</option>
					</select>
					<label>Nombre del maestro</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select>
						<option value="" disabled selected>Selecciona la materia</option>
						<option value="1">Option 1</option>
						<option value="2">Option 2</option>
						<option value="3">Option 3</option>
					</select>
					<label>Nombre de la materia</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s5">
					<select>
						<option value="" disabled selected>Selecciona la práctica</option>
						<option value="1">Option 1</option>
						<option value="2">Option 2</option>
						<option value="3">Option 3</option>
					</select>
					<label>Nombre de la práctica</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select>
						<option value="" disabled selected>Selecciona la hora</option>
						<option value="1">Option 1</option>
						<option value="2">Option 2</option>
						<option value="3">Option 3</option>
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