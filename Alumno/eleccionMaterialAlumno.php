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
		<!--<div class="col s2 offset-s1">Fecha: 01/12/2015</div>-->
	</div>
	<p id="textoMaterial">Por favor, seleccione los articulos y la cantidad de ellos que necesitará para su práctica.</p>
	<div class="row">
		<div class="col s10 offset-s1">
			<table class="responsive-table highlight bordered" id="tbEleccionMaterial">
				<!--<thead>
					<tr>
						<th data-field="cantidad">Cantidad</th>
						<th data-field="descripcion">Descripción</th>
						<th data-field="disponibles">Disponibles</th>
					</tr>
				</thead>-->
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col s6 offset-s4">
			<button class="btn waves-effect waves-light  green darken-2" type="submit" name="action" id="btnAceptar">Aceptar
			</button>
			<a class="waves-effect waves-light btn red darken-1" id="btnCancelar">Cancelar</a>
		</div>
	</div>
</div>
</div>