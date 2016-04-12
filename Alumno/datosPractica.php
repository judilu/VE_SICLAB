<div id="datosPractica2">
	<?php require_once('../data/conexion.php');?>
	<h5>Datos de la práctica</h5>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s5">
					<select id="cmbMateriaAlumno">
						<option value="" disabled selected>Selecciona la materia</option>
						<?php						
						$conexion		= conectaBDSIE();
						$consulta 		= sprintf("select MATCVE, MATNCO from DMATER");
						$res 			= mysql_query($consulta);
						while($row = mysql_fetch_array($res))
						{
							echo '<option value="'.$row["MATCVE"].'">'.$row["MATNCO"].'</option>';
						}?>
					</select>
					<label>Nombre del maestro</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select id="cmbMaestroPractica">
						<option value="" disabled selected>Selecciona el maestro</option>
						<!--<?php						
						$conexion		= conectaBDSIE();
						$consulta 		= sprintf("select p.PERCVE,p.PERNOM,p.PERAPE from DPERSO p INNER JOIN DGRUPO g on p.PERCVE=g.PERCVE inner JOIN DMATER m on g.MATCVE=m.MATCVE GROUP BY p.PERCVE");
						$res 			= mysql_query($consulta);
						while($row = mysql_fetch_array($res))
						{
							echo '<option value="'.$row["PERCVE"].'">'.$row["PERAPE"."PERNOM"].'</option>';
						}?>-->
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