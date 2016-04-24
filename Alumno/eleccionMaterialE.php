<div id="seccionMaterialE">
	<h5>Elección de material</h5>
	<div class="row">
		<div class="input-field col s3">
			<input placeholder="" id="txtFechaActualEM" type="text" class="validate" disabled>
		</div>
		<div class="input-field col s1">
			<input placeholder="" id="txtCveCalExt" type="hidden" class="validate" disabled>
		</div>
	</div>
	<div class="row">
		<div class="col s3">
			<div class="input-field col s12">
				<input placeholder="" id="txtNumeroControlDep" type="text" class="validate" disabled>
				<label for="txtNumeroControlPrestamo">No. de control dependencia</label>
			</div>
		</div>
		<div class="col s5">
			<div class="input-field col s12">
				<input placeholder="" id="txtNombreEncargadoDep" type="text" class="validate" disabled>
				<label for="txtNombreAluPrestamo">Nombre encargado</label>
			</div>
		</div>
	</div>
	<p id="textoMaterial">Por favor, seleccione los articulos y la cantidad de ellos que necesitará para su práctica.</p>
	<!--<div class="row">
		<div class="input-field col s2">
			<input type="checkbox" id="chbElegirOtroMaterialDep">
			<label for="chbElegirOtroMaterial">Otro material</label>
		</div>
		<div class="input-field col s5 offset-s1">
			<select id="cmbMaterialesLabDep">
				<option value="" disabled selected>Selecciona el articulo</option>
			</select>
			<label>Agregar otro artículo</label>
		</div>
		<div class="input-field col s1">
		<input id="txtNumArtMatDep" type="number" min="1" max="10" value="1"class="validate" disabled>
			<label for="txtNumArtMatDep">Cantidad</label>
		</div>
		<div class="col s3">
			<a id="btnAgregarArtDep" class="waves-effect waves-light btn amber darken-2" style="display:none"><i class="material-icons left">add</i>Agregar</a>
		</div>
	</div>-->
	<div class="row">
		<div class="col s10 offset-s1">
			<table class="bordered" id="tbMaterialExterno">
				<thead>
					<tr>
						<th data-field="nombre" class="col s6">Nombre del artículo</th>
						<th data-field="cantidad" class="col s3">Cantidad</th>
						<th data-field="accion" class="col s3">Acción</th>
					</tr>
				</thead>
				<tbody id="bodyArtExterno">
					
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col s7 offset-s4">
			<button class="btn waves-effect waves-light  green darken-2" type="submit" name="action" id="btnAceptarEleccionMatDep">Aceptar
			</button>
			<button class="btn waves-effect waves-light  green darken-2" type="submit" name="action" id="btnAceptarSinMat">Aceptar sin material
			</button>
			<a class="waves-effect waves-light btn red darken-1" id="btnCancelarEleccionMatDep">Cancelar</a>
		</div>
	</div>
</div>
