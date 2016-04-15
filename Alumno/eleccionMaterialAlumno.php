<div id="eleccionMaterialAlumno2">
	<div class="row">
		<div class="col s3">
			<div class="input-field col s12">
				<input placeholder="" id="txtNumeroControlPrestamo" type="text" class="validate" disabled>
				<label for="txtNumeroControlPrestamo">No. de control</label>
			</div>
		</div>
		<div class="col s5">
			<div class="input-field col s12">
				<input placeholder="" id="txtNombreAluPrestamo" type="text" class="validate" disabled>
				<label for="txtNombreAluPrestamo">Nombre</label>
			</div>
		</div>
	</div>
	<p id="textoMaterial">Por favor, seleccione los articulos y la cantidad de ellos que necesitará para su práctica.</p>
	<div class="row">
		<div class="input-field col s5 offset-s1">
			<select id="cmbMaterialesLab">
				<option value="" disabled selected>Selecciona la materia</option>
			</select>
			<label>Agregar otro artículo</label>
		</div>
		<div class="input-field col s2">
		<input id="txtNumArtMat" type="number" min="1" max="20" value="1"class="validate">
			<label for="txtNumArtMat">Cant. de articulos</label>
		</div>
		<div class="col s3">
			<a id="btnAgregarArtAlu" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
		</div>
	</div>
	<div class="row">
		<div class="col s10 offset-s1">
			<table class="responsive-table highlight bordered" id="tbEleccionMaterial">
				<thead>
					<tr>
						<th data-field="cantidad">Nombre del artículo</th>
						<th data-field="descripcion">Cantidad</th>
						<th data-field="disponibles">Acción</th>
					</tr>
				</thead>
				<tbody id="bodyArtAlumno">
					
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col s6 offset-s4">
			<button class="btn waves-effect waves-light  green darken-2" type="submit" name="action" id="btnAceptarEleccionMat">Aceptar
			</button>
			<a class="waves-effect waves-light btn red darken-1" id="btnCancelarEleccionMat">Cancelar</a>
		</div>
	</div>
</div>
</div>