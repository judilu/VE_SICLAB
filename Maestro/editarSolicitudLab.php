<div id = "editarSolLab">
		<?php require_once('../data/conexion.php');?>
		<div class="row" id="editarSol">
			<h5>Editar Solicitud</h5>
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6">
						<select id="cmbMateriaE">

						</select>
						<label>Materia</label>
					</div>
					<div class="input-field col s3">
						<select id="cmbHoraMatE">
							
						</select>
						<label>Hora de la materia</label>
					</div>
					<div class="input-field col s3">
						<input id= "txtFechaSE" placeholder= " " type="date" class="datepicker">
						<label class="active" for="txtFechaSE">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<select id="cmbPracticaE">
							
						</select>
						<label>Práctica</label>
					</div>
					<div class="input-field col s6">
						<select id="cmbLaboratorioE">
							
						</select>
						<label>Laboratorio</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<select id="cmbHoraPractE">
							
						</select>
						<label>Hora de la práctica</label>
					</div>
					<div class="input-field col s2 offset-s1">
						<input id="txtCantAlumnosE" type="number" min="1" max="45" value="1"class="validate">
						<label for="txtCantAlumnosE">Cant. de alumnos</label>
					</div>
					<div class="input-field col s6">
						<textarea id="textarea1E" class="materialize-textarea"></textarea>
						<label for="textarea1E">Motivo de uso</label>
					</div>
				</div>
				<div class="row">
					<div class="col s4 offset-s9">
						<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterialE">Elegir material</a>
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
								<option value="" disabled selected>Seleccione el material</option>
								<option value="110">ALUMINIO</option>
								<option value="115">AMONIACO</option>
								<option value="120">AMPERIMETRO</option>
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
							<table id="tbMaterialSolEE" class="bordered">
								<thead>
									<tr>
										<th data-field="txtCantidaEd" class="col s2">Cantidad</th>
										<th data-field="txtDescripcionE" class="col s8">Descripción</th>
										<th data-field="txtHoraE" class="col s2">Acción</th>
									</tr>
								</thead>
								<tbody id="bodyArtE">
									<!-- <tr>
										<td class="col s2">3</td>
										<td class="col s8">Osciloscopio</td>
										<td class="col s2"><a name = 1 class="btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1"><i class="material-icons">delete</i></a></td>";
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