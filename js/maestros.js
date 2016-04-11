var inicioMaestro = function ()
{
	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	var articulosAgregados = new Array();
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
		}
		);
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
					//$("#tbSolAceptadas a").on("click",practicaRealizada);
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
		$("#tbSolAceptadas a").on("click",practicaRealizada);	
	}//Termina función de solicitudes Aceptadas
	//Empieza función para liberar una solicitud realizada a aceptadas
	var practicaRealizada = function(evt)
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
    }//Termina función de crear nueva solicitud
    //Empieza función de elegir material
    var elegirMaterial = function()
    {
    	articulosAgregados = Array();
    	$("#nuevaMaestro").hide();
    	$("#eleccionMaterial").show("slow");
       //limpiar tabla de agregarMaterial
       $("#bodyArt").html(" "); 
    }//Termina función de elegir material
    //Empieza función agregar articulo
    var agregarArt = function()
    {
    	//aquiEmpieza todo
    	var artCve = $("#cmbMaterialCat" ).val();
    	var artNom = $("#cmbMaterialCat option:selected").text();
    	articulosAgregados.push(artCve);
    	console.log(articulosAgregados);
    	var parametros = "opc=agregarArt1"+
    						"&artCve="+artCve+
    						"&artNom="+artNom+
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
    				$("#bodyArt").append(response.renglones);
					//$(".btnEliminarArt").on("click",eliminarArt);
				}
				else
					console.log("no hizo nada");
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión articuloAgregado");	
			}
		});
		//modifique
		$(".btnEliminarArt").on("click",eliminarArt);
    }//Termina función agregar articulo
    //Comienza función de eliminar Articulo
    var eliminarArt = function()
    {
    	alert("hola");
    	console.log($(this).closest("tr"));
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
	    var solId = parseInt($(this).attr("name"));
	    var parametros = "opc=editarSolicitud1"+
	    "&solId="+solId+
	    "&id="+Math.random();
	    $.ajax({
	    	cache:false,
	    	type: "POST",
	    	dataType: "json",
	    	url:'../data/maestros.php',
	    	data: parametros,
	    	success: function(response){
	    		if(response.respuesta == true)
	    		{
       				/*//llenado datos 
       				//$("#cmbMateriaE").text("hola");
       				$("#cmbHoraMatE").val("hola");
       				$("#txtFechaE").val("2016-03-25");
       				$("#cmbPracticaE").val("hola");
       				$("#cmbHoraPractE").val("hola");
       				$("#txtCantAlumnosE").val("20");
       				$("#cmbLaboratorioE").val("hola");
       				$("#textarea1E").val("nose");*/
       				$("#cmbMateriaE").html(" ");
       				$("#cmbMateriaE").append(response.combo);
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
    var elegirMaterialE = function()
    {
    	//ocultar elementos
    	$("#editarSol").hide();
    	$("#eleccionMaterialE").show("slow");
    }//Termina función elegirMaterial de editar
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
    var altaNuevaSol = function()
    {
    	//carrito
    	console.log(articulosAgregados);
    	//fin carrito
    	//insertar una nueva solicitud
    	// cadena.substring(índice donde inicia recordando que el primero es cero,indice - 1)
	    	if(($("#cmbMateria").val())!= null && ($("#cmbHoraMat").val())!= null && ($("#txtFechaS").val())!= "" && ($("#cmbPractica").val())!= null && ($("#cmbHoraPract").val())!= null && ($("#txtCantAlumnos").val())!= "" && ($("#textarea1").val())!= "" && articulosAgregados != "" )
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
		        var art  = "";
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
		                     		"&id="+Math.random();
		                     $.ajax({
		                     	cache:false,
		                     	type: "POST",
		                     	dataType: "json",
		                     	url:'../data/maestros.php',
		                     	data: parametros,
		                     	success: function(response){
		                     		if(response.respuesta == true)
		                     		{
		                     			swal("La solicitud fue creada con éxito!", "Da clic en el botón OK!", "success");
		                     			//Limpiar campos
		                     			
		                     		}
		                     		else
		                     		{
		                     			sweetAlert("Error", "No se pudo crear la solicitud!", "error");
		                     		}
		                     	},
		                     	error: function(xhr, ajaxOptions,x){
		                     		console.log("Error de conexión articulo");
		                     	}
		                     });

	    	}
	    	else
	    	{
	    		sweetAlert("Error", "Debe llenar todos los campos!", "error");
	    	}
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
	//para botones que son creados dinamicamente primero se coloca:
	//el nombre de el id de la tabla que lo contiene despues el on y despues el evento
	//y de ahi el nombre del boton que desencadenara el evento
	$("#tbSolRealizadas").on("click","#btnPracticaRealizada",practicaRealizada);
	$("#btnSolicitudesRealizadas").on("click",solRealizadas);
	$("#btnNuevaSolicitud").on("click",solNueva);
	$("#btnElegirMaterial").on("click",elegirMaterial);
	$("#btnFinalizarNS").on("click",altaNuevaSol);
	$("#btnRegresar").on("click",solNueva);
	$("#btnAgregarArt").on("click",agregarArt);
	//Configuramos los eventos Menu Reportes
	$("#btnListaAsistencia").on("click",listaAsistencia);
	$("#btnRegresarla").on("click",regresar);
}
$(document).on("ready",inicioMaestro);