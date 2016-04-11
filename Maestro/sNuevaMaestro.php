<div id="solNuevaMaestro">
		<?php require_once('../data/conexion.php');?>
		<div class="row" id="nuevaMaestro">
			<h5>Nueva solicitud</h5>
			<div class="col s12">
				<div class="row">
					<div class="input-field col s8">
						<select id="cmbMateria">
							<option value="" disabled selected>Seleccione la materia</option>
							<?php						
							$conexion		= conectaBDSIE();
							$consulta 		= sprintf("select m.MATCVE, m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE = 920 and g.PDOCVE ='2161' and g.GRUBAS = ' ' and g.INSNUM > 0");
							$res 			= mysql_query($consulta);
							while($row = mysql_fetch_array($res))
							{
								echo '<option value="'.$row["MATCVE"].'">'.$row["MATNCO"].'</option>';
							}?>
						</select>
						<label>Materia</label>
					</div>
					<div class="input-field col s2">
						<select id="cmbHoraMat">
							<option value="" disabled selected>Seleccione la hora</option>
							<option value="1">10:00</option>
							<option value="2">01:00</option>
							<option value="3">15:00</option>
						</select>
						<label>Hora de la materia</label>
					</div>
					<div class="input-field col s2">
						<input id= "txtFechaS" placeholder= " " type="date" class="datepicker">
						<label class="active" for="txtFechaS">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s8">
						<select id="cmbPractica">
							<option value="" disabled selected>Seleccione la práctica</option>
							<?php		
							$conexion		= conectaBDSICLAB();
							$consulta 		= sprintf("select clavePractica, tituloPractica from lbpracticas WHERE estatus = 'V'");
							$res 			= mysql_query($consulta);
							while($row = mysql_fetch_array($res))
							{
								echo '<option value="'.$row["clavePractica"].'">'.$row["tituloPractica"].'</option>';
							}?>
						</select>
						<label>Práctica</label>
					</div>
					<div class="input-field col s2">
						<select id="cmbHoraPract">
							<option value="" disabled selected>Seleccione la hora</option>
							<option value="1">12:30</option>
							<option value="2">01:00</option>
							<option value="3">15:00</option>
						</select>
						<label>Hora de la práctica</label>
					</div>
					<div class="input-field col s2">
						<input id="txtCantAlumnos" type="number" class="validate">
						<label for="txtCantAlumnos">Cant. de alumnos</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s8">
						<select id="cmbLaboratorio">
							<option value="" disabled selected>Seleccione el laboratorio</option>
							<?php		
							$conexion		= conectaBDSICLAB();
							$consulta 		= sprintf("select claveLaboratorio, nombreLaboratorio from lblaboratorios");
							$res 			= mysql_query($consulta);
							while($row = mysql_fetch_array($res))
							{
								echo '<option value="'.$row["claveLaboratorio"].'">'.$row["nombreLaboratorio"].'</option>';
							}?>
						</select>
						<label>Laboratorio</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<textarea id="textarea1" class="materialize-textarea"></textarea>
						<label for="textarea1">Motivo de uso</label>
					</div>
					<div class="col s4 offset-s1">
						<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterial">Elegir material</a>
					</div>
				</div>
			</div>
		</div>
		<!-- //elección material -->
		<div id="eleccionMaterial">
			<div class="row">
				<div class="col s12">
					<div class="row">
						<div class="input-field col s4 offset-s1">
							<select id="cmbMaterialCat">
								<option value="" disabled selected>Seleccione el material</option>
								<option value="110">ALUMINIO</option>
								<option value="115">AMONIACO</option>
								<option value="120">AMPERIMETRO</option>
							</select>
							<label>Materiales</label>
						</div>
						<div class="col s3">
							<a id="btnAgregarArt" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
						</div>
					</div>
					<div class="row">
						<div class="col s10 offset-s1">
							<table id="tbMaterialSol" class="bordered">
								<thead>
									<tr>
										<th data-field="txtCantidad" class="col s2">Cantidad</th>
										<th data-field="txtDescripcion" class="col s8">Descripción</th>
										<th data-field="txtHora" class="col s2">Acción</th>
									</tr>
								</thead>
								<tbody id="bodyArt">
									<!-- <tr>
										<td class="col s2"><input type="number" min="1" max="20" value="1"></td>
										<td class="col s8">Osciloscopio</td>
										<td class="col s2"><a name = 1 class="btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1"><i class="material-icons">delete</i></a></td>";
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col s4 offset-s4">
							<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNS">Finalizar</a>
							<a class="waves-effect waves-light btn amber darken-2" id="btnRegresar">Regresar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Termina elección material -->
	</div>