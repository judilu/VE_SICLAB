var inicioMaestro = function ()
{
	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	var articulos = new Array();
	var articulosAgregados = new Array();
	var numArticulos = new Array();
	var articulosE = new Array();
	var articulosAgregadosE = new Array();
	var numArticulosE = new Array();
	//eventos menu Solicitudes
	//Empieza función salir del sistema
	var salir = function()
	{
		swal({   	
			title: "¿Estas seguro que deseas salir?",   
			text: "",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Si",   
			cancelButtonText: "No",   
			closeOnConfirm: false,   closeOnCancel: false 
		}, 
		function(isConfirm)
		{   
			if (isConfirm) 
			{ 
				var parametros = "opc=salir1"+
				"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:"../data/funciones.php",
					data: parametros,
					success: function(response){
						if(response.respuesta)
						{
							document.location.href= "acceso.php";
						}
						else
						{
							console.log(response.respuesta);
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						console.log("Error de conexión salir");
					}
				});
			} 
			else 
			{
				solAceptadas();
				swal("OK..!","Aún sigues en el sistema", "error");
			} 
		});
	}//Termina función salir del sistema
	//Empieza función de solicitudes Aceptadas
	var solAceptadas = function()
	{
		//ocultar los div
		$("#sNuevaMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		//contenido dinamico
		var parametros = "opc=solicitudesAceptadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbSolAceptadas").html(" ");
					$("#tbSolAceptadas").append(response.renglones);
					$("#tbSolAceptadas a").on("click",practicaRealizada);
				}
				else
					sweetAlert("No hay solicitudes..!", "Debe crear una solicitud antes", "error");
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión sol aceptadas");
				console.log(xhr);
			}
		});
		$("#sAceptadasMaestro").show("slow");
	}//Termina función de solicitudes Aceptadas
	//Empieza función para liberar una solicitud realizada a aceptadas
	var practicaRealizada = function()
	{
		//contenido dinamico
		var realid = $(this).attr("name");
		var parametros = "opc=liberarPractica1"+
		"&clave="+realid+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					solAceptadas();
					swal("Practica realizada..!", "Buen trabajo..!", "success");
				}
				else
				{
					sweetAlert("No existe esa solicitud..!", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión realizadas");
			}
		});	
	}//Termina función para liberar una solicitud realizada a aceptadas
	//Empieza función de solicitudes pendientes
	var solPendientes = function()
	{
		$("#sNuevaMaestro").hide();
		$("#sAceptadasMaestro").hide();
		$("#editarSolicitudLab").hide();
		$("#sRealizadas").hide();
		//Contenido Dinamico
		var parametros = "opc=solicitudesPendientes1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tabSolPendientes").html("");
					$("#tabSolPendientes").append(response.renglones);
					/*$("#tabSolPendientes").on("click", ".btnEditarSolicitudLab" , editarSolicitudLab);
					$("#tabSolPendientes").on("click", ".btnEliminarSolicitudLab" , eliminarSolicitud);*/
				}
				else
					sweetAlert("No hay solicitudes pendientes", "Han aceptado todas tus solicitudes o no ha enviado ninguna solicitud", "error");
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión pendientes");	
			}
		});
		$("#sPendientesMaestro").show("slow");
		$("#solicitudesPendientesLab").show("slow");
		//modifique
		$("#tabSolPendientes").on("click", ".btnEditarSolicitudLab" , editarSolicitudLab);
		$("#tabSolPendientes").on("click", ".btnEliminarSolicitudLab" , eliminarSolicitud);	
	}//Termina función de solicitudes pendientes
	//Empieza función de solicitudes realizadas
	var solRealizadas = function()
	{
		//ocultar los div
		$("#sNuevaMaestro").hide();
		$("#sAceptadasMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		//contenido dinamico
		var parametros = "opc=solicitudesRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbSolRealizadas").html(" ");
					$("#tbSolRealizadas").append(response.renglones);
				}
				else
					sweetAlert("No hay solicitudes realizadas", "Debes liberar la práctica realizada", "error");
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión srealizadas");	
			}
		});
		$("#sRealizadas").show("slow");
	}//Termina función de solicitudes realizadas
	//Empieza función de crear nueva solicitud
	var solNueva = function()
	{
		$("#sAceptadasMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		$("#eleccionMaterial").hide();
		$("#sNuevaMaestro").show("slow");
		$("#nuevaMaestro").show("slow");
		//modificación combo
		var parametros = "opc=comboMat1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/funciones.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#cmbMateria").html(" ");
					//$("#cmbMateria").append(response.combo);
					$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbMateria").append($("<option></option>").attr("value",response.claveMat[i]).text(response.nombreMat[i]));
					}
					$("cmbMateria").trigger('contentChanged');
					$('select').material_select();
					//limpiar campos
					//Combo hora materia
					$("#cmbHoraMat").html(" ");
					$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					//combo Practica
					$("#cmbPractica").html(" ");
					$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
					$('select').material_select();
					//combo laboratorio
					$("#cmbLaboratorio").html(" ");
					$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
					$('select').material_select();
					//Combo hora practica
					$("#cmbHoraPract").html(" ");
					$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					//txt
					$("#txtFechaS").val("dd/mm/aaaa");									
					$("#txtCantAlumnos").val("1");
					$("#textarea1").val("");
					$("#txtNumArt").val("1");
					articulos = Array();
					articulosAgregados = Array();
					numArticulos = Array();
					$("#bodyArt").html("");
				}
				else
				{
					$("#cmbMateria").html(" ");
					$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
					$('select').material_select();
					sweetAlert("No tiene materias", "Es posible que no tenga materias asignadas!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión combomat");	
			}
		});
    }//Termina función de crear nueva solicitud
    //Empieza función de elegir material
    var elegirMaterial = function()
    {
    	$("#nuevaMaestro").hide();
    	$("#eleccionMaterial").show("slow");
    	articulos = Array();
    	articulosAgregados = Array();
    	numArticulos = Array();
    	//llenar el combo de materiales
    	var laboratorio = $("#cmbLaboratorio").val();
    	var parametros = "opc=comboEleArt1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbMaterialCat").html(" ");
    				$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
    				for (var i = 0; i < response.con; i++) 
    				{
    					$("#cmbMaterialCat").append($("<option></option>").attr("value",response.comboCveArt[i]).text(response.comboNomArt[i]));
    				}
    				$("cmbMaterialCat").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#cmbMaterialCat").html(" ");
    				$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
    				$('select').material_select();
    				sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "error");
    			}
    		},
    		error: function(xhr, ajaxOptions,x){
    			console.log("Error de conexión combomat");	
    		}
    	});
       //limpiar tabla de agregarMaterial
       $("#bodyArt").html(" "); 
    }//Termina función de elegir material
    //Empieza función agregar articulo
    var agregarArt = function()
    {
    	//aquiEmpieza todo
    	var artCve = $("#cmbMaterialCat" ).val();
    	var artNom = $("#cmbMaterialCat option:selected").text();
    	var num    = $("#txtNumArt").val();
	    articulos.push(artNom);
	    articulosAgregados.push(artCve);
	    numArticulos.push(num);
	    var parametros = "opc=agregarArt1"+
	    "&artCve="+artCve+
	    "&artNom="+artNom+
	    "&num="+num+
	    "&id="+Math.random();
	    $.ajax({
		    cache:false,
		    type: "POST",
		    dataType: "json",
		    url:"../data/maestros.php",
		    data: parametros,
		    success: function(response)
		    {
		    	if(response.respuesta == true)
		    	{
		    				$("#txtNumArt").val("1");
		    				$("#bodyArt").append(response.renglones);
		    				$(".btnEliminarArt").on("click",eliminarArt);
							//formar de nuevo el combo
							llenarcomboEleArt();		
				}//termina if
				else
				{
					console.log("no agrego articulos");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión articuloAgregado");	
			}
		});
    }//Termina función agregar articulo
    var llenarcomboEleArt = function()
    {
    	var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregados.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#cmbLaboratorio").val();
    	var parametros 	= "opc=comboEleArt1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregados[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminar(comboclaArt,i));
						comboArt 	= (eliminar(comboArt,i));				
					}
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCat").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCat").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión combomat");	
			}
		});
}
var eliminar = function(arreglo,posicion)
{
	var ar = arreglo;
	var p  = posicion;
	var c  = ar.length;
	var af = Array();
	for (var i=0; i < c; i++) 
	{
		if(i != posicion)
		{
			af.push(ar[i]);
		}
	}
	return af;
}
    //Comienza función de eliminar Articulo
    var eliminarArt = function()
    {
    	var art = ($(this).attr("name"));
    	var i = articulosAgregados.indexOf(art);
    	articulos = eliminar(articulos,i);
    	articulosAgregados = eliminar(articulosAgregados,i);
    	numArticulos = eliminar(numArticulos,i);
    	//construir tabla
    	var parametros = "opc=construirTbArt1"+
    	"&articulosAgregados="+articulosAgregados+
    	"&articulos="+articulos+
    	"&numArticulos="+numArticulos+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#bodyArt").html("");
    				$("#bodyArt").append(response.renglones);
    				$(".btnEliminarArt").on("click",eliminarArt);
					//formar de nuevo el combo
					llenarcomboEleArt();
				}//termina if
				else
				{
					console.log("no elimino");
					$("#bodyArt").html("");
					llenarcomboEleArt();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión articuloAgregado");	
			}
		});
    	//termina construcción de tabla
    }//Termina función de eliminar Articulo
    //Empieza función editar solicitud
    var editarSolicitudLab = function()
    {
	    //ocultar elementos
	    //$(this).closest("td").children("input").val();
	    $("#solicitudesPendientesLab").hide();
	    $("#eleccionMaterialE").hide();
	    //Contenido Dinamico
	    $("#editarSolicitudLab").show("slow");
	    articulosE 			= Array();
    	articulosAgregadosE	= Array();
    	numArticulosE 		= Array();
    	var con   			= 0
	    var solId 			= parseInt($(this).attr("name"));
	    var parametros 		= "opc=editarSolicitud1"+
	    						"&solId="+solId+
	    						"&id="+Math.random();
	    $.ajax({
	    	cache:false,
	    	type: "POST",
	    	dataType: "json",
	    	url:'../data/maestros.php',
	    	data: parametros,
	    	success: function(response)
	    	{
	    		if(response.respuesta == true)
	    		{
       				//llenado datos
       				$("#txtMateriaE").val(response.materia);
       				$("#txtHoraMatE").val(((response.horas[0]).substring(0,2))+":00");
       				//$("#txtFechaSE").val("12/12/2016");
       				$("#txtPracticaE").val(response.practica);
       				$("#txtLabE").val(response.laboratorio);
       				$("#txtLabE").attr("name",response.claveLab);
       				$("#cmbHoraPractE").val(); //modificar
       				$("#txtCantAlumnosE").val(response.cantidad);
       				$("#textarea1E").val(response.motivoUso);
       				//combo
       				comboHoraPracE(response.claveLab,response.horaPractica);
       				//llenar tabla y combo
       				con =((response.materiales['claveMat']).length);
       				for (var i =0; i<con; i++) 
       				{
       					articulosAgregadosE.push((response.materiales['claveMat'][i]));
       					articulosE.push((response.materiales['nomMat'][i]));
						numArticulosE.push((response.materiales['cantidad'][i]));
       				}
       				console.log(articulosAgregadosE);
       			}
       			else
       			{
       				sweetAlert("Error", "No existe esa solicitud..!", "error");
       			}
       		},
       		error: function(xhr, ajaxOptions,x){
       			console.log("Error de conexion editar");
       		}
       	});
    }//Termina función editar solicitud
    var agregarArtE = function()
    {
    	//aquiEmpieza todo
    	var artCve = $("#cmbMaterialCatE" ).val();
    	var artNom = $("#cmbMaterialCatE option:selected").text();
    	var num    = $("#txtNumArtE").val();
	    articulosE.push(artNom);
	    articulosAgregadosE.push(artCve);
	    numArticulosE.push(num);
	    var parametros = "opc=agregarArt1"+
	    "&artCve="+artCve+
	    "&artNom="+artNom+
	    "&num="+num+
	    "&id="+Math.random();
	    $.ajax({
		    cache:false,
		    type: "POST",
		    dataType: "json",
		    url:"../data/maestros.php",
		    data: parametros,
		    success: function(response)
		    {
		    	if(response.respuesta == true)
		    	{
		    				$("#txtNumArt").val("1");
		    				$("#bodyArt").append(response.renglones);
		    				$(".btnEliminarArtE").on("click",eliminarArtE);
							//formar de nuevo el combo
							llenarcomboEleArtE();		
				}//termina if
				else
				{
					console.log("no agrego articulos");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión articuloAgregado");	
			}
		});
    }
    var elegirMaterialE = function()
    {
    	//ocultar elementos
    	$("#editarSol").hide();
    	$("#eleccionMaterialE").show("slow");
    	//llenar combo y crear tabla
    	//Crear tabla y crear combo
    	construirTabla();
    	llenarcomboEleArtE();
    }//Termina función elegirMaterial de editar
    var construirTabla = function()
    {
    	var parametros = "opc=construirTbArt1"+
    	"&articulosAgregados="+articulosAgregadosE+
    	"&articulos="+articulosE+
    	"&numArticulos="+numArticulosE+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#bodyArtE").html("");
    				$("#bodyArtE").append(response.renglones);
    				//$(".btnEliminarArtE").on("click",eliminarArtE);
					//formar de nuevo el combo
					llenarcomboEleArtE();
				}//termina if
				else
				{
					console.log("no elimino");
					$("#bodyArt").html("");
					llenarcomboEleArt();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión construir tabla");	
			}
		});
    }
    var llenarcomboEleArtE = function()
    {
    	var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregadosE.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#txtLabE").attr("name");
    	console.log(laboratorio);
    	var parametros 	= "opc=comboEleArt1"+
    						"&laboratorio="+laboratorio+
    						"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregadosE[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminar(comboclaArt,i));
						comboArt 	= (eliminar(comboArt,i));				
					}
					console.log(comboclaArt);
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCatE").html(" ");
					$("#cmbMaterialCatE").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCatE").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCatE").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexion combomat editar");
				console.log(xhr);	
			}
		});
    }
    var regresarEditar = function()
    {
    	//ocultar elementos
    	$("#eleccionMaterialE").hide();
    	$("#editarSol").show("slow");
    }
    //empieza función de eliminar solicitud
    var eliminarSolicitud = function()
    {
    	$(this).closest("tr").remove();
    	var solId = parseInt($(this).attr("name"));
    	swal({   	
    		title: "¿Esta seguro que desea eliminar la solicitud?",   
    		text: "Una vez eliminada la solicitud, esta ya no existira..!",   
    		type: "error",   
    		showCancelButton: true,   
    		confirmButtonColor: "#DD6B55",   
    		confirmButtonText: "Si",   
    		cancelButtonText: "No",   
    		closeOnConfirm: false,   
    		closeOnCancel: false 
    	},  function(isConfirm)
    	{   
    		if (isConfirm) 
    		{ 
    			var parametros = "opc=eliminarSolicitud1"+
    			"&solId="+solId+
    			"&id="+Math.random();
    			$.ajax({
    				cache:false,
    				type: "POST",
    				dataType: "json",
    				url:"../data/maestros.php",
    				data: parametros,
    				success: function(response)
    				{
    					if(response.respuesta)
    					{
    						swal("La solicitud fue eliminada con éxito!", "Da click en el botón", "success");
    						solPendientes();
    					}
    					else
    					{
    						sweetAlert("La solicitud no fue eliminada", "", "error");
    					}
    				},
    				error: function(xhr, ajaxOptions,x)
    				{
    					console.log("Error de conexión eliminar s");
    				}
    			});
    		} 
    		else 
    		{
    			swal("OK..!","La solicitud no fue eliminada..!", "error");
    		} 
    	});
    }//fin función eliminar solicitud
    //inicio de funcion de capacidad de laboratorio
    var capacidadLab = function()
    {
    	var laboratorio = $("#cmbLaboratorio").val();
    	var parametros 	  = "opc=capacidadLab1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				if(parseInt($("#txtCantAlumnos").val()) <= response.capacidad)
    				{
						//console.log("entro t");
						//console.log($("#txtCantAlumnos").val());
						//console.log(response.capacidad);
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión validar capacidad");	
			}
		});

    }//fin de funcion de capacidad de laboratorio
    //inicio de funcion para dar de alta una nueva solicitud de lab
    var altaNuevaSol = function()
    {
    	//insertar una nueva solicitud
    	if(($("#cmbMateria").val())!= null && ($("#cmbHoraMat").val())!= null && ($("#txtFechaS").val())!= "" && ($("#cmbPractica").val())!= null && ($("#cmbHoraPract").val())!= null && ($("#txtCantAlumnos").val())!= "" && ($("#textarea1").val())!= "" && articulos != "" && articulosAgregados != "" && numArticulos != "")
    	{
    		var res=parseInt($("#txtCantAlumnos").attr("max"));
    		var cantalu = parseInt($("#txtCantAlumnos").val());
    		if(cantalu <= res)
    		{
    			var f  = new Date();
    			var dd = f.getDate();
    			var mm = (f.getMonth())+1;
    			(dd<10) ? (dd="0"+dd) : dd;
    			(mm<10) ? (mm="0"+mm) : mm;
    			var fe  = (dd+"/"+mm+"/"+f.getFullYear());
    			var ff  = $("#txtFechaS").val();
    			var a  = ff.substring(0,4);
    			var m  = ff.substring(5,7);
    			var d  = ff.substring(8,10);
    			var fs = d+"/"+m+"/"+a;
    			var hs  = $("#cmbHoraPract option:selected").text();
    			var lab = $("#cmbLaboratorio").val();
    			var uso = $("#textarea1").val();
    			var prac = $("#cmbPractica").val();
    			var mat = $("#cmbMateria").val();
    			var gp  = $("#cmbHoraMat option:selected").text();
				var gpo = parseInt(gp.substring(0,2))//segun la hora se saca el grupo
				var cant = $("#txtCantAlumnos").val();
				var n = (($("#tbMaterialSol tr").length)-1);
				//var con  = ($("#tbMaterialSol tr").length);
				var parametros = "opc=nuevaSol1"+
				        			"&fe="+fe+
									"&fs="+fs+
									"&hs="+hs+
									"&lab="+lab+
									"&uso="+uso+
				        			"&prac="+prac+
				        			"&mat="+mat+
				        			"&gpo="+gpo+
				        			"&cant="+cant+
				        			"&art="+articulosAgregados+
				        			"&num="+numArticulos+
				        			"&n="+n+
				        			"&id="+Math.random();
				$.ajax({
				        cache:false,
				        type: "POST",
				        dataType: "json",
				        url:'../data/maestros.php',
				        data: parametros,
				        success: function(response)
				        {
				        		if(response.respuesta == true && response.respuesta2 == true && response.respuesta3 == true)
				        		{
				                    //limpiar datos
				                    $("#txtFechaS").val("dd/mm/aaaa");									
				                    $("#txtCantAlumnos").val("1");
				                    $("#textarea1").val("");
				                    $("#txtNumArt").val("1");
				                    articulos = Array();
				                    articulosAgregados = Array();
				                    numArticulos = Array();
				                    $("#bodyArt").html("");
									//combo materia
									$("#cmbMateria").html(" ");
									$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
									$('select').material_select();
									//Combo hora materia
									$("#cmbHoraMat").html(" ");
									$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
									$('select').material_select();
									//combo Practica
									$("#cmbPractica").html(" ");
									$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
									$('select').material_select();
									//combo laboratorio
									$("#cmbLaboratorio").html(" ");
									$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
									$('select').material_select();
									//Combo hora practica
									$("#cmbHoraPract").html(" ");
									$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
									$('select').material_select();
									solNueva();
									swal("La solicitud fue creada con éxito!", "Da clic en el botón OK!", "success");				
								}
								else
								{
									if(response.respuesta3==false)
									{
										sweetAlert("Error", "Ya existe una calendarización del laboratorio en esa fecha y hora!", "error");
									}
									else
									{	
										sweetAlert("Error", "No se pudo crear la solicitud!", "error");
									}
								}
							},
							error: function(xhr, ajaxOptions,x)
							{
								console.log("Error de conexión articulo");
							}
				});
			}//Termina if de checar si la capacidad del lab es correcta
			else
			{
				sweetAlert("Error", "La capacidad seleccionada es superior a la que permite el laboratorio!", "error");
			}
	   	}//Termina if de checar campos vacios
	   	else
	   	{
	    	sweetAlert("Error", "Debe llenar todos los campos!", "error");
	   	}
    }//fin de funcion para dar de alta una nueva solicitud de lab
    //inicio de funcion para llenar el combo de hora de materia
    var comboHoraMat = function()
    {
    	var hh = "";
    	var materia = $("#cmbMateria").val();
    	var parametros = "opc=comboMatHr1"+
    	"&materia="+materia+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbHoraMat").html(" ");
    				$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				for (var i = 0; i < response.cont; i++) 
    				{
						//arreglar la hora
						hh = ((response.comboHr[i]).substring(0,2)+":00");
						$("#cmbHoraMat").append($("<option></option>").attr("value",response.cont).text(hh));
					}
					$("cmbHoraMat").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#cmbHoraMat").html(" ");
					$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas para la materia seleccionada", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión combomat");	
			}
		});
		//inicializa los otros combos
		//combo Practica
		$("#cmbPractica").html(" ");
		$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
		$('select').material_select();
		//combo laboratorio
		$("#cmbLaboratorio").html(" ");
		$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
		$('select').material_select();
		//Combo hora practica
		$("#cmbHoraPract").html(" ");
		$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
    }//fin de funcion para llenar el combo de hora de materia
    //inicio de funcion para llenar el combo de practica
    var comboPract = function()
    {
    	var materia = $("#cmbMateria").val();
    	var parametros = "opc=comboPract1"+
    	"&materia="+materia+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbPractica").html(" ");
    				$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				for (var i = 0; i < response.con; i++) 
    				{

    					$("#cmbPractica").append($("<option></option>").attr("value",response.comboCvePrac[i]).text(response.comboTitPrac[i]));
    				}
    				$("cmbPractica").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#cmbPractica").html(" ");
    				$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				$('select').material_select();
    				sweetAlert("No existen prácticas", "Es posible que no existan prácticas asociadas a dicha materia!", "error");
    			}
    		},
    		error: function(xhr, ajaxOptions,x){
    			console.log("Error de conexión comboPrac");	
    		}
    	});
    }//fin de funcion para llenar el combo de practica
    //inicio de funcion para llenar el combo de laboratorio
    var comboLab = function()
    {
    	var practica   = $("#cmbPractica").val();
    	var parametros = "opc=comboLab1"+
    	"&practica="+practica+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbLaboratorio").html(" ");
    				$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");	
    				for (var i = 0; i < response.con; i++) 
    				{
    					$("#cmbLaboratorio").append($("<option></option>").attr("value",response.comboCveLab[i]).text(response.comboNomLab[i]));
    				}
    				$("cmbLaboratorio").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#cmbLaboratorio").html(" ");
    				$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
    				$('select').material_select();
    				sweetAlert("No existen laboratorio", "Es posible que no existan laboratorios asociados a dicha práctica!", "error");
    			}
    		},
    		error: function(xhr, ajaxOptions,x)
    		{
    			console.log("Error de conexión comboPrac");	
    		}
    	});
    }//fin de funcion para llenar el combo de laboratorio
    //inicio de funcion para llenar el combo de la hora de la practica
    var comboHoraPrac = function()
    {
    	var laboratorio   = $("#cmbLaboratorio").val();
    	var parametros 	  = "opc=comboHoraPrac1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbHoraPract").html(" ");
    				$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					$("#txtCantAlumnos").attr("max",response.capacidad)
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPract").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPract").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPract").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#cmbHoraPract").html(" ");
					$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión comboPrac");	
			}
		});
    }//fin de funcion para llenar el combo de la hora de la practica
	var comboHoraPracE = function(lab,hor)
    {
    	var hora 			= hor;
    	var laboratorio   	= lab;
    	var parametros 	  	= "opc=comboHoraPrac1"+
    							"&laboratorio="+laboratorio+
    							"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#cmbHoraPractE").html(" ");
    				$("#cmbHoraPractE").html("<option value='"+hora+"' disabled selected>"+hora+"</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					//$("#txtCantAlumnos").attr("max",response.capacidad)
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPractE").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPractE").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPractE").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#cmbHoraPractE").html(" ");
					$("#cmbHoraPractE").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión comboPracE");	
			}
		});
    }
	//eventos menu Reportes
	var listaAsistencia = function()
	{
		$("#selecionarLista").hide();
		$("#lista").show();
	}
	var regresar = function()
	{
		$("#lista").hide();
		$("#selecionarLista").show("slow");
	}
	//Configuramos el evento del Tab
	$("#salirTab").on("click",salir);
	$("#solicitudestab").on("click",solAceptadas);
	//Configuramos los eventos Menu Solicitudes
	$("#btnSolicitudesAceptadas").on("click",solAceptadas);
	$("#btnSolicitudesPendientes").on("click",solPendientes);
	$("#btnElegirMaterialE").on("click",elegirMaterialE);
	$("#btnRegresarE").on("click",regresarEditar);
	$("#btnRegresarPen").on("click",solPendientes);
	//para botones que son creados dinamicamente primero se coloca:
	//el nombre de el id de la tabla que lo contiene despues el on y despues el evento
	//y de ahi el nombre del boton que desencadenara el evento
	$("#tbSolRealizadas").on("click","#btnPracticaRealizada",practicaRealizada);
	$("#btnSolicitudesRealizadas").on("click",solRealizadas);
	$("#btnNuevaSolicitud").on("click",solNueva);
	//eventos de los combos
	$("#cmbMateria").on("change",comboHoraMat);
	$("#cmbHoraMat").on("change",comboPract);
	$("#cmbPractica").on("change",comboLab);
	$("#cmbLaboratorio").on("change",comboHoraPrac);
	//dddddd
	$("#btnElegirMaterial").on("click",elegirMaterial);
	$("#btnFinalizarNS").on("click",altaNuevaSol);
	$("#btnRegresar").on("click",solNueva);
	$("#btnAgregarArt").on("click",agregarArt);
	//Configuramos los eventos Menu Reportes
	$("#btnListaAsistencia").on("click",listaAsistencia);
	$("#btnRegresarla").on("click",regresar);
}
$(document).on("ready",inicioMaestro);