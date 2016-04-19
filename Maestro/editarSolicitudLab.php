<div id = "editarSolLab">
		<div class="row" id="editarSol">
			<h5>Editar Solicitud</h5>
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6">
						<input disabled placeholder="" id="txtMateriaE" type="text" class="validate">
						<label class="active" for="txtMateriaE">Materia</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id="txtHoraMatE" type="text" class="validate">
						<label class="active" for="txtHoraMatE">Hora de la materia</label>
					</div>
					<div class="input-field col s3">
						<input id= "txtFechaSE" placeholder= " " type="date" class="datepicker">
						<label class="active" for="txtFechaSE">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<input disabled id= "txtPracticaE" placeholder= " " type="text" class="validate">
						<label class="active" for="txtPracticaE">Práctica</label>
					</div>
					<div class="input-field col s6">
						<input disabled id= "txtLabE" placeholder= " " type="text" class="validate">
						<label class="active" for="txtLabE">Laboratorio</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<select id="cmbHoraPractE">
							
						</select>
						<label>Hora de la práctica</label>
					</div>
					<div class="input-field col s2 offset-s1">
						<input disabled id="txtCantAlumnosE" type="number" min="1" value="1"class="validate">
						<label class="active" for="txtCantAlumnosE">Cant. de alumnos</label>
					</div>
					<div class="input-field col s6">
						<textarea disabled id="textarea1E" class="materialize-textarea"></textarea>
						<label class="active" for="textarea1E">Motivo de uso</label>
					</div>
				</div>
				<div class="row">
					<div class="col s8 offset-s7">
						<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterialE">Elegir material</a>
						<a class="waves-effect waves-light btn green darken-2" id="btnRegresarPen">Regresar</a>
					</div>
				</div>
			</div>
		</div>
		<!-- //elección material -->
		<div id="eleccionMaterialE">
			<div class="row">
				<div class="col s12">
					<div class="row">
						<div class="input-field col s6 offset-s1">
							<select id="cmbMaterialCatE">
								<!-- <option value="" disabled selected>Seleccione el material</option>
								<option value="110">ALUMINIO</option>
								<option value="115">AMONIACO</option>
								<option value="120">AMPERIMETRO</option> -->
							</select>
							<label>Materiales</label>
						</div>
						<div class="input-field col s2">
							<input id="txtNumArtE" type="number" min="1" max="20" value="1"class="validate">
							<label for="txtNumArtE">Cant. de articulos</label>
						</div>
						<div class="col s3">
							<a id="btnAgregarArtE" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
						</div>
					</div>
					<div class="row">
						<div class="col s10 offset-s1">
							<table id="tbMaterialSolE" class="bordered">
								<thead>
									<tr>
										<th data-field="txtCantidad" class="col s2">Cantidad</th>
										<th data-field="txtDescripcion" class="col s8">Descripción</th>
										<th data-field="txtHora" class="col s2">Acción</th>
									</tr>
								</thead>
								<tbody id="bodyArtE">
									<!-- <tr>
										<td class="col s2">3</td>
										<td class="col s8">Osciloscopio</td>
										<td class="col s2"><a name = 1 class="btnEliminarArtE btn-floating btn-large waves-effect waves-light red darken-1"><i class="material-icons">delete</i></a></td>";
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col s4 offset-s4">
							<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNSE">Finalizar</a>
							<a class="waves-effect waves-light btn amber darken-2" id="btnRegresarE">Regresar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Termina elección material -->
	</div>