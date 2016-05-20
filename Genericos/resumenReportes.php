<section id="resumenReportes">
		<div class="row">
	        <div class="col s4 m4">
	          <div class="card blue-grey lighten-2">
	            <div class="card-content white-text">
	              <span class="card-title" id="practicasNR">Prácticas no realizadas</span>
	              <!--<p>Mes actual: 500 Alumnos</p>
	              <p>Mes anterior: 700 Alumnos</p>
	              <p>Mes con mayor uso: Marzo</p>-->
	            </div>
	          </div>
	        </div>
	        <div class="col s4 m4">
	          <div class="card red lighten-2">
	            <div class="card-content white-text">
	              <span class="card-title" id="masSolicitado">Materiales</span>
	              
	            </div>
	          </div>
	        </div>
	        <div class="col s4 m4">
	          <div class="card orange lighten-1">
	            <div class="card-content white-text">
	              <span class="card-title" id="alumnosActuales"></span>
	              
	            </div>
	          </div>
	        </div>
	        <script type="text/javascript">
	    		var datos = $.ajax({
	    		url:'../data/datosgrafica.php',
	    		type:'post',
	    		dataType:'json',
	    		async:false    		
	    	}).responseText;
	    	
	    	datos = JSON.parse(datos);
	    	google.charts.load("visualization", "1", {packages:["corechart"]});
	      	google.charts.setOnLoadCallback(dibujarGrafico);
	      
	      	function dibujarGrafico() {
	        	var data = google.visualization.arrayToDataTable(datos);

	        	var options = {
	          	title: 'GRAFICA USO MENSUAL DEL LABORATORIO',
	          	hAxis: {title: 'MESES', titleTextStyle: {color: 'black'}},
	          	vAxis: {title: 'VISITAS', titleTextStyle: {color: 'black'}},
	          	backgroundColor:'grey ligthten-3',
	          	legend:{position: 'bottom', textStyle: {color: 'blue', fontSize: 12}},
	          	width:620,
	            height:370
	        	};

	        	var grafico = new google.visualization.ColumnChart(document.getElementById('grafica'));
	        	grafico.draw(data, options);
      		}
      	</script>
	        <div class="col s8 m8">
	          <div class="card grey lighten-3">
	            <div class="card-content black-text" id="grafica">
	              <span class="card-title">Grafica Uso Por Mes</span>
	              
	            </div>
	          </div>
	        </div>
	        <div class="col s4 m4">
	          <div class="card grey lighten-3">
	            <div class="card-content black-text">
	              <span class="card-title">Materiales sin stock</span>
	              <table id="tbMaterialesSinStock">
  
  				</table>
	            </div>
	          </div>
	        </div>
	        <div class="col s12 m12">
	          <div class="card grey lighten-3">
	            <div class="card-content black-text">
	              <span class="card-title">Próximos apartados</span>
					<table id="tbProximosApartados">
					        
					</table>
	            </div>
	          </div>
	        </div>
      </div>
</section>
