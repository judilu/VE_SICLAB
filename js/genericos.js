var inicioG = function()
 {
 	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	$('.collapsible').collapsible({
      accordion : false}); // A setting that changes the collapsible behavior to expandable instead of the default accordion style
	var articulosPrestados = new Array();
	var articulosExt = new Array();
	var articulosAgregadosExt = new Array();
	var numArticulosExt = new Array();
    //Salir del sistema
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
    				success: function(response)
    				{
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
    			swal("OK..!","Aún sigues en el sistema", "error");
    		} 
    	});
    }
	//Prestamos de matertial a alumnos y externos
	var prestamosPendientes = function()
	{
		if(tipoUsu != 5)
		{
			$("#atenderSolicitud").hide("slow");
			$("#alumnosSancionados").hide("slow");
			$("#solicitudesEnProceso").hide("slow");
			$("#solicitudesPendientes").show("slow");
			$("#solicitudesPendientes2").show("slow");
			$("#tabSolPendientesAlumnos").html(" ");
			$("#loaderImageG").show();
			var parametros 	= "opc=prestamosPendientes1"+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtnombreAlumnoPrestamo").val(response.nombre);
						$("#tabSolPendientesAlumnos").html(" ");
						$("#tabSolPendientesAlumnos").append(response.renglones);
						$("#tabSolPendientesAlumnos #btnAtenderPrestamo").on("click",atenderPrestamoMaterial);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay prestamos pendientes!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión prestamos pendientes");
				}
			});
		}
	}
	var atenderPrestamoMaterial = function()
	{
		$(this).closest('tr').remove();
		articulosPrestados = Array();
		$("#solicitudesPendientes2").hide("slow");
		$("#tbListaMaterialPrestamo").html("");
		$("#bodyArtSolicitados").html("");
		$("#loaderImageG").show();
		var clavePrestamo= $(this).attr('name');
		var parametros 	= "opc=atenderPrestamo1"
						+"&clavePrestamo="+clavePrestamo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtclavePrestamo").val(response.clavePrestamo);
					$("#txtcodigoBarrasPrestamo").val("");
					$("#tbListaMaterialPrestamo").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo atender la solicitud!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión atender prestamo material");
			}
		});
		$("#atenderSolicitud").show("slow");
		$("#atenderSolicitud2").show("slow");

	}
	var agregarArticuloPrestamo = function()
	{
		if(($("#txtcodigoBarrasPrestamo").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo 	= $("#txtcodigoBarrasPrestamo").val();
			var clavePrestamo 			= $("#txtclavePrestamo").val();
			var parametros 				= "opc=agregaArticulos1"
											+"&identificadorArticulo="+identificadorArticulo
											+"&clavePrestamo="+clavePrestamo
											+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtcodigoBarrasPrestamo").val("");
						$("#tbArticulosSolicitados > tbody").append("<tr><td>"+response.idu+"</td><td>"+response.nomArt+"</td></tr>");
						articulosPrestados.push(response.idu);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el articulo para agregarlo");
				}
			});
		}
	}
	var guardarPrestamoPendiente = function()
	{
		$("#loaderImageG").show();
		var listaArt 		= articulosPrestados;
		var clavePrestamo 	= $("#txtclavePrestamo").val();
		var parametros 		= "opc=guardaPrestamoPendiente1"
								+"&listaArt="+listaArt
								+"&clavePrestamo="+clavePrestamo
								+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:'../data/genericos.php',
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					swal("Prestamo finalizado con éxito!", "Da clic en el botón OK!", "success");
					prestamosPendientes();
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Error", "No se pudo finalizar el prestamo!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión guarda prestamo pendiente");
			}
		});
		$("#txtclavePrestamo").val("");
	}
	var eliminaPrestamoPendiente = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var clavePrestamo 	= $("#btnEliminarPrestamo").attr('name');
			$(this).closest("tr").remove();
			$("#loaderImageG").show();
			var parametros 		= "opc=eliminaPrestamoPendiente1"
								+"&clavePrestamo="+clavePrestamo
								+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						swal("Prestamo eliminado con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "No se pudo eliminar el prestamo!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión elimina prestamo pendiente");
				}
			});
		}
		else
		{
			$("#loaderImageG").hide();
			$("#btnEliminarPrestamo").hide();
			sweetAlert("Error", "No tienes permisos para eliminar el préstamo", "error");
		}
	}
	var prestamosProceso = function()
	{
		$("#loaderImageG").show();
		$("#tabSolProcesoAlumnos").html("");
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#alumnosSancionados").hide("slow");
		var parametros 	= "opc=prestamosProceso1"
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tabSolProcesoAlumnos").html("");
					$("#tabSolProcesoAlumnos").append(response.renglones);
					$("#tabSolProcesoAlumnos #btnDevolucionMaterial").on("click",devolucionPrestamo);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay prestamos en proceso!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión prestamos en proceso");
			}
		});
		$("#solicitudesEnProceso").show("slow");
		$("#solicitudesEnProceso2").show("slow");
	}
	var listaSanciones = function()
	{	
		$("#loaderImageG").show();
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#solicitudesEnProceso").hide("slow");
		var parametros 	= "opc=listaSanciones1"
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tabListaSanciones").html("");
					$("#tabListaSanciones").append(response.renglones);
					$("#tabListaSanciones #btnQuitaSancion").on("click",quitaSancion);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay personas sancionadas!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de sanciones");
			}
		});
		$("#alumnosSancionados").show("slow");
		$("#listaSanciones2").show("slow");
	}
	var quitaSancion = function ()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());	
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			$("#loaderImageG").show();
			$(this).closest("tr").remove();
			var claveSancion = $(this).attr('name');
			var parametros 	= "opc=quitaSanciones1"+
								"&claveSancion="+claveSancion+
								"&fecha="+fe+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("Sanción eliminada con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("La sanción no se pudo eliminar!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión quitar sanción");
				}
			});
		}
	}
	var aplicaSancion = function()
	{	
		$("#devolucionMaterial2").hide("slow");
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());	
		var idu 			= $(this).attr("name");
		var clavePrestamo 	= $("#txtClavePrestamoDevolucion").val();
		$("#txtNumeroControlSancion").val($("#txtNControlAluDev").val());
		$("#txtNombreAlumnoSancion").val($("#txtNombreAluDev").val());
		$("#txtIdentificadorArtSancion").val(idu);
		$("#txtFechaSancion").val(fe);
		$("#loaderImageG").show();
		var nc 				= $("#txtNumeroControlSancion").val();
		var nom 			= $("#txtNombreAlumnoSancion").val();
		var parametros 		= "opc=aplicaSancion1"
							+"&identificador="+idu
							+"&clavePrestamo="+clavePrestamo
							+"&nc="+nc
							+"&nom="+nom
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#cmbSanciones").html(" ");
					$("#txtClavePrestamoSancion").val(response.prestamo);
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbSanciones").append($("<option></option>").attr("value",response.claveSancion[i]).text(response.nombreSancion[i]));
					}
					$("#cmbSanciones").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					console.log("Error de botón aplicar sanción");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión aplica sanción");
			}
		});
		$("#aplicaSanciones").show("slow");
	}
	var  guardaSancionAlumno = function()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());	
		var clavePrestamo 	= $("#txtClavePrestamoSancion").val();
		var idu 			= $("#txtIdentificadorArtSancion").val();
		var nc 				= $("#txtNumeroControlSancion").val();
		var claveSancion 	= $("#cmbSanciones").val();
		var fecha 			= $("#txtFechaSancion").val();
		var comentario 		= $("#txtComentariosSanciones").val();
		$("#loaderImageG").show();
		var parametros 	= "opc=guardaSancion1"
							+"&clavePrestamo="+clavePrestamo
							+"&idu="+idu
							+"&nc="+nc
							+"&claveSancion="+claveSancion
							+"&fecha="+fecha
							+"&comentario="+comentario
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					swal("Sanción aplicada con éxito!", "Da clic en el botón OK!", "success");
					listaSanciones();
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo aplicar la sanción!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión al guardar la sanción");
			}
		});
	}
	var devolucionPrestamo = function()
	{
		$("#loaderImageG").show();
		$("#solicitudesEnProceso2").hide("slow");
		$("#aplicaSanciones").hide("slow");
		$("#tbListaArticulosDevolucion").html("");
		var clavePrestamo 	= $(this).attr('name');
		var parametros 	= "opc=devolucionPrestamo1"
						+"&clavePrestamo="+clavePrestamo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtNControlAluDev").val(response.numeroControl);
					$("#txtNombreAluDev").val(response.nombreAlumno);
					$("#tbListaArticulosDevolucion").append(response.renglones);
					$("#txtClavePrestamoDevolucion").val(response.clavePrestamo);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe el prestamo!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión devolución prestamo");
			}
		});
		$("#devolucionMaterial").show("slow");
		$("#devolucionMaterial2").show("slow");
	}
	var guardarDevolucionPrestamo = function()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());
		var horaActual 				= new Date();
		var hora 					=horaActual.getHours();
		var minutos 				=horaActual.getMinutes();
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var hora					= hora + ":" + minutos;

		var identificadorArticulo 	= $(this).attr('name');
		var clavePrestamo 			= $("#txtClavePrestamoDevolucion").val();
		$(this).closest("tr").remove();
		$("#loaderImageG").show();
		var parametros 		= "opc=guardaDevolucion1"
							+"&clavePrestamo="+clavePrestamo
							+"&identificadorArticulo="+identificadorArticulo
							+"&horaDevolucion="+hora
							+"&fechaDevolucion="+fe
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:'../data/genericos.php',
			data: parametros,
			success: function(response){
				if(response.respuesta)
				{
					$("#loaderImageG").hide();
					swal("Devolución guardada con éxito!", "Da clic en el botón OK!", "success");
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Error", "No se pudo guardar la devolución!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión guarda prestamo devolución");
			}
		});
	}
	//Laboratorios
	//solicitudes pendientes de laboratorio...
	//funcion para aceptar una solicitud, introduciendo datos faltantes para agendarla
	var aceptarSolicitudLab = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			$("#verPrincipal").hide("slow");
			$("#solicitudesPendientesLab2").hide("slow");
			$("#aceptarSolLab").show("slow");
			$("#guardarSolicitud").show("slow");
			$("#verMasSolicitud").show("slow");
			$("#txtComentariosSol").val("");
			$("#loaderImageG").show();
			var claveSol= $(this).attr('name');
			var parametros 	= "opc=obtenerDatosSolLab1"+
								"&clave="+claveSol+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtFechaAsignada").val(response.fecha);
						$("#txtHoraAsignada").val(response.hora);
						$("#txtClaveSol").val(claveSol);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("La solicitud no existe!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión aceptar solicitud de laboratorio");
				}
			});
		}
	}
	//funcion para guardar una solicitud de laboratorio
	var sGuardaCanderalizada = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var claveSol	 = $("#txtClaveSol").val();
			var fechaAsignada= $("#txtFechaAsignada").val();
			var horaAsignada = $("#txtHoraAsignada").val();
			var firmaJefe 	 = "0000";
			var comentarios  = $("#txtComentariosSol").val();
			if (($("#txtClaveSol").val())!="" && ($("#txtComentariosSol").val())!="" && ($("#txtFechaAsignada").val())!="" && ($("#txtHoraAsignada").val())!="") 
			{
				$("#loaderImageG").show();
				var parametros 	= "opc=guardaSolicitudLab1"
								+"&clave="+claveSol
								+"&fecha="+fechaAsignada
								+"&hora="+horaAsignada
								+"&firmaJefe="+firmaJefe
								+"&comentarios="+comentarios
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:"../data/genericos.php",
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							sweetAlert("La solicitud fue calendarizada con éxito!", "Da click en el botón OK", "success");
							sLaboratorioPendientes();
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("La solicitud no se calendarizó elige otra fecha u hora!", " ", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión guarda solicitud laboratorio");
					}
				});
			}
		}
	}
	//funcion para eliminar una solicitud de laboratorio
	var eliminarSolLab = function(){
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var claveSol= $(this).attr('name');
			$(this).closest('tr').remove();
			$("#loaderImageG").show();
			var parametros 	= "opc=eliminaSolicitudLab1"
							+"&clave="+claveSol
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						sweetAlert("La solicitud fue eliminada con éxito!", "Da click en el botón OK", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se pudo eliminar la solicitud!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión elimina solicitud de laboratorio");
				}
			});
		}
	}
	var sLaboratorioNuevas = function()
	{
		var f  = new Date();
    	var dd = f.getDate();
    	var mm = (f.getMonth())+1;
    	(dd<10) ? (dd="0"+dd) : dd;
    	(mm<10) ? (mm="0"+mm) : mm;
    	var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
		$("#eleccionMaterialExt").hide();
		$("#sAceptadasLab").hide("slow");
		$("#sPendientesLab").hide("slow");
		$("#verMasSolicitud").hide("slow");
		$("#aceptarSolLab").hide("slow");
		$("#guardarSolicitud").hide("slow");
		$("#sNuevaLabExternos").show("slow");
		$("#nuevaExterno").show("slow");
		$("#datosDependencia").hide("slow");
		$("input").val("");
		$("#txtCantAlumnosExterno").val("1");
		$("textarea").val("");
		$("#chbOtraDependencia").prop('checked', false); ;
		$("#txtFechaSolExterno").val(fe);
		$("#txtFechaSolExterno").attr("min",fe);
		//inicializar combo
		//combo Lab
		$("#cmbLaboratorioExterno").html(" ");
		$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
		//combo horaLab
		$("#cmbHoraPractExterno").html(" ");
    	$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");	
		$("#loaderImageG").show();	
			var parametros  = "opc=listaDependencias1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#txtFechaSolExterno").attr("max",response.fecha);
						$("#loaderImageG").hide();
						$("#cmbNombreDependencias").html(" ");
						$("#cmbNombreDependencias").html("<option value='' disabled selected>Selecciona la dependencia</option>");
						
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreDependencias").append($("<option></option>").attr("value",response.claveDependencia[i]).text(response.nombreDependencia[i]));
						}
						$("#cmbNombreDependencias").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No existen dependencias", "", "error");
					}
					if(response.respuesta2)
					{
						$("#loaderImageG").hide();
						$("#cmbPracticaExterno").html(" ");
						$("#cmbPracticaExterno").html("<option value='' disabled selected>Selecciona la práctica</option>");

						for (var i = 0; i < response.contador2; i++) 
						{
							$("#cmbPracticaExterno").append($("<option></option>").attr("value",response.clavePractica[i]).text(response.nombrePractica[i]));
						}
						$("#cmbPracticaExterno").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No existen prácticas", "", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
	}
	var agregarArtExt = function()
	{
		//aquiEmpieza todo
    	var artCve = $("#cmbMaterialCatExt" ).val();
    	var artNom = $("#cmbMaterialCatExt option:selected").text();
    	var num    = $("#txtNumArtExt").val();
	    articulosExt.push(artNom);
	    articulosAgregadosExt.push(artCve);
	    numArticulosExt.push(num);
	    //construir tabla
	    construirTablaExt();
	}
	var eliminarArtExt = function()
    {
    	var art = ($(this).attr("name"));
    	var i = articulosAgregadosExt.indexOf(art);
    	articulosExt = eliminarG(articulosExt,i);
    	articulosAgregadosExt = eliminarG(articulosAgregadosExt,i);
    	numArticulos = eliminarG(numArticulosExt,i);
    	//construir tabla
    	construirTablaExt();
    }
    var eliminarG = function(arreglo,posicion)
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
	var elegirMatExterno = function()
	{
		if($("#cmbNombreDependencias").val() != null || $("#txtNombreDependencia").val() != "")
		{
			if($("#txtFechaSolExterno").val() != "" && $("#cmbPracticaExterno").val() != null && $("#cmbLaboratorioExterno").val() != null
				&& $("#cmbHoraPractExterno").val() != null && $("#txtCantAlumnosExterno").val() != "" && $("#txtMotivoUsoExterno").val() != "")
			{
				//ocultar elementos
		    	$("#nuevaExterno").hide();
		    	$("#eleccionMaterialExt").show("slow");
		    	$("#txtNumArtExt").val("1");
		    	//llenar combo y crear tabla
		    	//Crear tabla y crear combo
		    	llenarcomboEleArtExt();
		    }
		}
	    else
	    {
			sweetAlert("Debe llenar todos los campos", "error");
	    }
	}
	var construirTablaExt = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=construirTbArtExt1"+
    	"&articulosAgregadosExt="+articulosAgregadosExt+
    	"&articulosExt="+articulosExt+
    	"&numArticulosExt="+numArticulosExt+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				$("#bodyArtExt").html("");
    				$("#bodyArtExt").append(response.renglones);
    				llenarcomboEleArtExt();
    				$("#txtNumArtExt").val("1");
    				$(".btnEliminarArtExt").on("click",eliminarArtExt);
					//formar de nuevo el combo
				}//termina if
				else
				{
					$("#loaderImageG").hide();
					console.log("no elimino");
					$("#bodyArtExt").html("");
					llenarcomboEleArtExt();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión construir tabla");	
			}
		});
	}
	var llenarcomboEleArtExt = function()
	{
		$("#loaderImageG").show();
		var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregadosExt.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#cmbLaboratorioExterno").val();
    	var parametros 	= "opc=comboEleArtExt1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregadosExt[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminarG(comboclaArt,i));
						comboArt 	= (eliminarG(comboArt,i));				
					}
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCatExt").html(" ");
					$("#cmbMaterialCatExt").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCatExt").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCatExt").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					$("#cmbMaterialCatExt").html(" ");
					$("#cmbMaterialCatExt").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión combomat");	
			}
		});
	}
	var comboLaboratoriosExt = function()
	{
		$("#loaderImageG").show();
		practica  = $("#cmbPracticaExterno").val();
		var parametros  = "opc=llenarcomboLabExt1"
							+"&practica="+practica
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						$("#cmbLaboratorioExterno").html(" ");
						$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbLaboratorioExterno").append($("<option></option>").attr("value",response.claveLab[i]).text(response.nombreLab[i]));
						}
						$("#cmbLaboratorioExterno").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						$("#cmbLaboratorioExterno").html(" ");
						$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
						sweetAlert("No existen laboratorios", "la practica seleccionada no tiene asignado ningun laboratorio", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
	}
	var comboHoraExt = function()
	{
		$("#loaderImageG").show();
		var laboratorio   = $("#cmbLaboratorioExterno").val();
    	var parametros 	  = "opc=llenarcomboHrLabExt1"
    						+"&laboratorio="+laboratorio
    						+"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				$("#cmbHoraPractExterno").html(" ");
    				$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					$("#txtCantAlumnosExterno").attr("max",response.capacidad);
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPractExterno").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPractExterno").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPractExterno").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					$("#cmbHoraPractExterno").html(" ");
					$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión comboPrac");	
			}
		});
	}
	var guardaSolLabExterno = function()
	{
		if($("#chbOtraDependencia").is(':checked'))
		{
			var dependencia 	= $("#txtNombreDependencia").val();
			var otra 			= 1;
		}
		else
		{
			var dependencia 	= $("#cmbNombreDependencias").val();
			var otra 			= 0;
		}
		$("#loaderImageG").show();
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());
		var ff 		= $("#txtFechaSolExterno").val();
		var a  = ff.substring(0,4);
    	var m  = ff.substring(5,7);
    	var d  = ff.substring(8,10);
    	var fecha = d+"/"+m+"/"+a;
		var practica 	= $("#cmbPracticaExterno").val();
		var laboratorio	= $("#cmbLaboratorioExterno").val();
		var hora		= $("#cmbHoraPractExterno option:selected").val();
		var cantAlu		= $("#txtCantAlumnosExterno").val();
		var motivo		= $("#txtMotivoUsoExterno").val();
		var nomEncargado= $("#txtNombreEncargado").val();
		var direccion 	= $("#txtDireccionDependencia").val();
		var telefono	= $("#txtTelefonoDependencia").val();
		var articulos	= articulosAgregadosExt;
		var cantArt		= numArticulosExt;
		var numeroLineas= (($("#tbMaterialSolExt tr").length)-1);
		var parametros 	= "opc=guardaSolLabExterno1"
							+"&dependencia="+dependencia
							+"&fechaEnvio="+fe
							+"&fechaSol="+fecha
							+"&practica="+practica
							+"&laboratorio="+laboratorio
							+"&hora="+hora
							+"&cantAlu="+cantAlu
							+"&motivo="+motivo
							+"&nomEncargado="+nomEncargado
							+"&direccion="+direccion
							+"&telefono="+telefono
							+"&articulos="+articulos
							+"&cantArt="+cantArt
							+"&otra="+otra
							+"&numeroLineas="+numeroLineas
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						if (response.otra == 1) 
						{
							swal("La solicitud fue enviada con éxito! El numero de control de la dependencia es: "+response.dependenciaN, "Da clic en el botón OK!", "success");
							sLaboratorioNuevas();
						}
						else
						{
							swal("La solicitud fue enviada con éxito! El numero de control de la dependencia es: "+response.dependenciaE, "Da clic en el botón OK!", "success");
							sLaboratorioNuevas();

						}
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se envió la solicitud!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión solicitudes pendientes de laboratorio");
				}
			});
	}
	var numeroControlLabExterno = function()
	{
		$("#txtNumeroControlDependencia").val($("#cmbNombreDependencias").val());
	}
	var checkOtraDependencia = function()
	{
		if ($("#chbOtraDependencia").is(':checked'))
		{
			$("#datosDependencia").show("slow");
			$("#txtNombreDependencia").removeAttr("disabled");
			$("#cmbNombreDependencias").html("");
			$("#cmbNombreDependencias").html("<option value='' disabled selected>Selecciona la dependencia</option>");
			$("#cmbNombreDependencias").trigger('contentChanged');
			$('select').material_select();
			$("#txtNumeroControlDependencia").val(" ");
		}
		else
		{
			$("#datosDependencia").hide("slow");
			$("#txtNombreDependencia").attr("disabled","disabled");
			sLaboratorioNuevas();
		}
	}
	var sLaboratorioPendientes = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			$("#sAceptadasLab").hide("slow");
			$("#sNuevaLabExternos").hide("slow");
			$("#verMasSolicitud").hide("slow");
			$("#aceptarSolLab").hide("slow");
			$("#guardarSolicitud").hide("slow");
			$("#tbPendientesLab").html("");
			$("#loaderImageG").show();
			var parametros 	= "opc=pendientesLab1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#tbPendientesLab").append(response.renglones);
						$("#tbPendientesLab #btnCalendarizado").on("click",aceptarSolicitudLab);
						$("#tbPendientesLab #btnVerMas").on("click",verMas);
						$("#tbPendientesLab #btnEliminarSolLab").on("click",eliminarSolLab);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay solicitudes de laboratorio pendientes!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión solicitudes pendientes de laboratorio");
				}
			});
			$("#sPendientesLab").show("slow");
			$("#solicitudesPendientesLab2").show("slow");
		}
	}
	var sLaboratorioAceptadas = function()
	{
		$("#loaderImageG").show();
		$("#sPendientesLab").hide("slow");
		$("#sNuevaLabExternos").hide("slow");
		$("#verMasSolicitud2").hide("slow");
		$("#tbAceptadasLab").html("");
		var claveCal = $(this).attr('name');
		var parametros 	= "opc=aceptadasLab1"
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbAceptadasLab").append(response.renglones);
					$("#tbAceptadasLab a").on("click",verMas2);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay solicitudes de laboratorio aceptadas!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión solicitudes aceptadas de laboratorio");
			}
		});
		$("#sAceptadasLab").show("slow");
		$("#solicitudesAceptadasLab2").show("slow");
	}
	var verMas = function()
	{		
		$("#loaderImageG").show();
		$("#solicitudesPendientesLab2").hide("slow");
		//contenido dinamico
		var clave = $(this).attr("name");
		$("#tbMaterialesPendientesLab").html("");
		var parametros = "opc=verMasLab1"
						+"&clave="+clave
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta)
				{
					$("#loaderImageG").hide();
					$("#txtFechaVM1").val(response.fecha);
					$("#txtHoraVM1").val(response.hora);
					$("#txtMaestroVM1").val(response.maestro);
					$("#txtPracticaVM1").val(response.practica);
					$("#tbMaterialesPendientesLab").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe esa solicitud..!", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión realizadas");
			}
		});
		$("#verMasSolicitud").show("slow");
		$("#verPrincipal").show("slow");	
	}
	var verMas2 = function()
	{		
		$("#solicitudesAceptadasLab2").hide("slow");
		$("#loaderImageG").show();
		//contenido dinamico
		var claveCal = $(this).attr("name");
		$("#tbMaterialesAceptadasLab").html("");
		var parametros = "opc=verMasLab2"
						+"&clave="+claveCal
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtFecha2").val(response.fechaAsignada);
					$("#txtHora2").val(response.horaAsignada);
					$("#txtMaestro2").val(response.maestro);
					$("#txtPractica2").val(response.practica);
					$("#tbMaterialesAceptadasLab").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe esa solicitud..!", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión realizadas");
			}
		});	
		$("#verMasSolicitud2").show("slow");
		$("#verPrincipal2").show("slow");
	}
	//Inventario
	//Busca un articulo en la lista de artículos del laboratorio
	var buscaArtInventario = function()
	{
		$("#loaderImageG").show();
		var articulo = $("#txtArticuloLista").val();
		var parametros 	= "opc=buscaArtLista1"
						+"&articulo="+articulo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbInventario").html("");
					$("#tbInventario").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe ese artículo..!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de artículos");
			}
		});
	}
	//Muestra la lista de artículos pertenecientes al laboratorio del usuario
	var listaArticulos = function()
	{
		$("#loaderImageG").show();
		$("#txtArticuloLista").val("");
		$("#altaArticulos").hide("slow");
		$("#bajaArticulos").hide("slow");
		$("#menuMtto").hide("slow");
		$("#editar").hide("slow");
		$("#peticionesPendientes").hide("slow");
		$("#peticionesArticulos").hide("slow");
		var parametros 	= "opc=listaArticulos1"
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbInventario").html("");
					$("#tbInventario").append(response.renglones);
				}
				else if (tipoUsu == 5) 
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay artículos pertenecientes al departamento..!", " ", "error");
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay artículos pertenecientes al laboratorio..!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de artículos");
			}
		});
		$("#listaArt").show("slow");
		$("#pantallaInventario").show("slow");
	}
	//Para mostrar pantalla de alta y que llene un combobox de los articulos que puede dar de alta
	var altaArticulos = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2) 
		{
			$("#loaderImageG").show();
			$("#pantallaInventario").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("input").val("");
			$("textarea").val("");
			var parametros  = "opc=listaArtAlta"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						$("#cmbNombreArtAlta").html(" ");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreArtAlta").append($("<option></option>").attr("value",response.claveMaterial[i]).text(response.nombreMaterial[i]));
						}
						$("#cmbNombreArtAlta").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay articulos disponibles", "", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
			$("#altaArticulos").show("slow");
		}
	}
	//guarda la alta de los articulos
	var altaInventario = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2) 
		{
			if(($("#txtModeloArtAlta").val())!="" && ($("#txtNumSerieAlta").val())!="" && ($("#cmbNombreArtAlta").val())!= null
				&& ($("#txtMarcaArtAlta").val())!="" && ($("#txtTipoContenedorAlta").val())!="" && ($("#txtUbicacionAlta").val())!="")
			{
				if($("#txtClaveKitAlta").val() == "" || $("#txtFechaCaducidadAlta").val() == "")
				{
					var claveKit 		= "0000";
					var fechaCaducidad	= "00/00/0000";
				}
				else
				{
					var claveKit 		= $("#txtClaveKitAlta").val();
					var fechaCaducidad	= $("#txtFechaCaducidadAlta").val();
				}
	       		$("#loaderImageG").show();
	       		var imagen						= " ";
	       		var modelo 						= $("#txtModeloArtAlta").val();
	       		var numeroSerie 				= $("#txtNumSerieAlta").val();
				var claveArticulo				= $("#cmbNombreArtAlta").val();//ocupo sacar el valor del select
				var marca						= $("#txtMarcaArtAlta").val();
				var tipoContenedor 				= $("#txtTipoContenedorAlta").val();
				var descripcionArticulo			= $("#txtDescripcionArtAlta").val();
				var descripcionUso				= $("#txtDescripcionUsoAlta").val();
				var unidadMedida 				= $("#cmbUm").val();
				var ubicacionAsignada			= $("#txtUbicacionAlta").val();
				var estatus						= "V";
				var parametros 	= "opc=altaInventario1"+"&claveArticulo="+claveArticulo
								+"&imagen="+imagen
								+"&modelo="+modelo
								+"&numeroSerie="+numeroSerie
								+"&marca="+marca
								+"&tipoContenedor="+tipoContenedor
								+"&descripcionArticulo="+descripcionArticulo
								+"&descripcionUso="+descripcionUso
								+"&unidadMedida="+unidadMedida
								+"&fechaCaducidad="+fechaCaducidad
								+"&claveKit="+claveKit
								+"&ubicacionAsignada="+ubicacionAsignada
								+"&estatus="+estatus
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response)
					{
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							swal("El articulo fue dado de alta con éxito! El identificador del artículo es: "+response.idu, "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("Error", "No se pudo insertar el articulo!", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión alta inventario");
					}
				});
			}
		}
	}
	//muestra la pantalla de baja articulos
	var bajaArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#altaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("input").val("");
			$("textarea").val("");
			$("#bajaArticulos").show("slow");
		}
	}
	//da de baja un articulo al presionar el boton dar de baja
	var bajaInventario = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			if($("#cmbTipoBaja").val()!= null && $("#txtMotivoDeBaja").val()!="" && $("#txtModeloArtBaja").val()!="")
			{
				$("#loaderImageG").show();
				var identificadorArticulo	= $("#txtCodigoBarrasBaja").val();//obtener el articulo a dar de baja
				var estatus 				= $("#cmbTipoBaja").val();
				var observaciones 			= $("#txtMotivoDeBaja").val()
				var parametros 	= "opc=bajaArticulos1"
								+"&identificadorArticulo="+identificadorArticulo
								+"&estatus="+estatus
								+"&observaciones="+observaciones
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							swal("El articulo fue dado de baja con éxito!", "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("Error", "No se pudo dar de baja el articulo!", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión baja de artículos");
					}
				});
			}
			else
			{
				$("#loaderImageG").hide();
				sweetAlert("Error", "Por favor llena todos los campos!", "error");
			}
		}
	}
	//buscar el articulo a dar de baja y regresa todos sus datos y los muestra 
	//en la pantalla de dar de baja, rellenando los campos
	var buscarArticulo = function() 
	{
		if(($("#txtCodigoBarrasBaja").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo= $("#txtCodigoBarrasBaja").val();
			var parametros  = "opc=buscaArticulos1"
							+"&identificadorArticulo="+identificadorArticulo
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtModeloArtBaja").val(response.modelo);
						$("#txtNumSerieBaja").val(response.numeroSerie);
						$("#txtNombreArtBaja").val(response.nombreArticulo);
						$("#txtMarcaArtBaja").val(response.marca);
						$("#txtFechaCaducidadBaja").val(response.fechaCaducidad);
						$("#txtDescripcionArtBaja").val(response.descripcionArticulo);
						$("#txtDescripcionUsoBaja").val(response.descripcionUso);
						$("#txtUnidadMedidaBaja").val(response.unidadMedida);
						$("#txtTipoContenedorBaja").val(response.tipoContenedor);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el articulo para darlo de baja");
				}
			});
		}
	}
	//Muestra la pantalla de enviar articulos a mantenimiento con sus dos botones
	var mantenimientoArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("#listaArtMtto").hide("slow");
			$("#listaMtto").hide("slow");
			$("#menuMtto").show("slow");
			$("#sEnvioMtto").show("slow");
		}
	}
	//Pantalla para enviar articulos a mantenimiento(solicitud)
	var enviaArtMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#listaArtMtto").hide("slow");
			$("#listaMtto").hide("slow");
			$("input").val("");
			$("textarea").val("");
			$("#menuMtto").show("slow");
			$("#sEnvioMtto").show("slow");
		}
	}
	//Pantalla para visualizar que articulos fueron enviados a mantenimiento
	var listaArtMtto = function()
	{
		$("#btnImprimirArtMtto").hide();
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#sEnvioMtto").hide("slow");
			$("#loaderImageG").show();
			var parametros 	= "opc=listaMantenimiento1"+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#btnImprimirArtMtto").show();
						$("#tbArticulosMtto").html("");
						$("#tbArticulosMtto").append(response.renglones);
						$("#tbArticulosMtto #btnRegresaDelMtto").on("click",regresaMtto);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay articulos en mantenimiento!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión lista de articulos en mantenimiento");
				}
			});
			$("#listaArtMtto").show("slow");
			$("#listaMtto").show("slow");
			$("#menuMtto").show("slow");
		}
	}
	//Regresa un articulo de mantenimiento
	var regresaMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#loaderImageG").show();
			var iduArt = $(this).attr('name');
			$(this).closest('tr').remove();
			//fecha del sistema
			var f  = new Date();
			var dd = f.getDate();
			var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
			var fe  = (dd+"/"+mm+"/"+f.getFullYear());
			//hora del sistema
			var horaActual 				= new Date();
			var hora 					=horaActual.getHours();
			var minutos 				=horaActual.getMinutes();
			(hora<10) ? (hora="0"+hora) : hora;
			(minutos<10) ? (minutos="0"+minutos) : minutos;
			var hora					= hora + ":" + minutos;
			var parametros  ="opc=regresaMtto1"
							+"&iduArt="+iduArt
							+"&fecha="+fe
							+"&hora="+hora
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("El artículo se regresó de mantenimiento con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "No se pudo registrar el regreso de mantenimiento!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión regreso de mantenimiento");
				}
			});
		}
	}
	//Busca el articulo que queremos enviar a mantenimiento
	var buscarArticuloMtto = function() 
	{
		if(($("#txtCodigoBarrasMtto").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo= $("#txtCodigoBarrasMtto").val();
			var parametros= "opc=buscaArticulos2"
			+"&identificadorArticulo="+identificadorArticulo
			+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtModeloArtMtto").val(response.modelo);
						$("#txtNumSerieMtto").val(response.numeroSerie);
						$("#txtNombreArtMtto").val(response.nombreArticulo);
						$("#txtMarcaArtMtto").val(response.marca);
						$("#txtFechaCaducidadMtto").val(response.fechaCaducidad);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el artículo para registrar el mantenimiento");
				}
			});
		}
	}
	//Cambia el estatus del articulo a M que es mantenimiento
	var guardaMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			if(($("#txtCodigoBarrasMtto").val())!="" && ($("#txtLugarReparacionMtto").val())!=""
				&& ($("#txtMotivoMtto").val())!="")
			{
				$("#loaderImageG").show();
				var f  = new Date();
				var dd = f.getDate();
				var mm = (f.getMonth())+1;
				(dd<10) ? (dd="0"+dd) : dd;
				(mm<10) ? (mm="0"+mm) : mm;
				var fe  = (dd+"/"+mm+"/"+f.getFullYear());
				var horaActual 				= new Date();
				var hora 					=horaActual.getHours();
				var minutos 				=horaActual.getMinutes();
				(minutos<10) ? (minutos="0"+minutos) : minutos;
				var hora					= hora + ":" + minutos;

				var identificadorArticulo	= $("#txtCodigoBarrasMtto").val();//obtener el articulo a dar de baja
				var observaciones 			= $("#txtMotivoMtto").val()
				var parametros 	= "opc=mantenimientoArticulos1"
								+"&identificadorArticulo="+identificadorArticulo
								+"&observaciones="+observaciones
								+"&horaMovimiento="+hora
								+"&fechaMovimiento="+fe
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							swal("El envío a mantenimiento quedó registrado!", "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("Error", "No se pudo registrar el envío a mentenimiento!", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión al registrar el mantenimiento del artículo");
					}
				});
			}
			else
			{
				$("#loaderImageG").hide();
				sweetAlert("Error", "Llene todos los campos", "error");
			}
		}
	}
	//Inventario
	//Peticiones de articulos
	var peticionesPendientesArt = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2 || tipoUsu == 5)
		{
			$("#loaderImageG").show();
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesArticulos").hide("slow");
			var parametros 	= "opc=peticionesPendientesArt1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#tbPeticionArticulos").html("");
						$("#tbPeticionArticulos").append(response.renglones);
						$("#tbPeticionArticulos #btnAceptaPeticionArt").on("click",aceptarPeticionArt);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay pedidos de artículos..!", " ", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión peticiones pendientes");
				}
			});
			$("#peticionesPendientes").show("slow");
		}
	}
	var aceptarPeticionArt = function()
	{
		if(tipoUsu == 5)
		{
			$("#loaderImageG").show();
			var clavePedido = $(this).attr('name');
			$(this).closest('tr').remove();
			var parametros  = "opc=aceptaPeticionArticulos1"
							+"&clavePedido="+clavePedido
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("Solicitud aceptada!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "No se pudo aceptar la solicitud!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión aceptar petición artículo");
				}
			});
		}
	}
	var peticionesArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			$("#loaderImageG").show();
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			var parametros  = "opc=comboArtPeticiones1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreArtPeticiones").append($("<option></option>").attr("value",response.nombreArt[i]).text(response.nombreArt[i]));
						}
						$("#cmbNombreArtPeticiones").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "No se pudo enviar la solicitud!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión enviar petición artículo");
				}
			});
			$("#peticionesArticulos").show("slow");
			$("input").val("");
			$("textarea").val("");
		}
	}
	var checkOtroArticulo = function()
	{
		if ($("#chbOtroArticulo").is(':checked'))
		{
			$("#txtNombreArticuloPeticion").removeAttr("disabled");
			$(".select-dropdown").attr("disabled","disabled");
		}
		else
		{
			$("#txtNombreArticuloPeticion").attr("disabled","disabled");
			$(".select-dropdown").removeAttr("disabled");
		}
	}
	var guardaPeticionArticulo = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			var nombreArticulo;
			if ($("#chbOtroArticulo").is(':checked'))
			{
				nombreArticulo = $("#txtNombreArticuloPeticion").val();
			}
			else
			{
				nombreArticulo = $("#cmbNombreArtPeticiones").val();
			}
			$("#loaderImageG").show();
			var f  = new Date();
			var dd = f.getDate();
			var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
			var fe  = (dd+"/"+mm+"/"+f.getFullYear());

			var cantidad 	= $("#txtCantidadPeticionArt").val();
			var marca 		= $("#txtMarcaPeticionArt").val();
			var modelo 		= $("#txtModeloPeticionArt").val();
			var motivo 		= $("#txtMotivoPedidoArt").val();

			var parametros= "opc=guardaPeticionArticulos1"
							+"&nombreArticulo="+nombreArticulo
							+"&cantidad="+cantidad
							+"&marca="+marca
							+"&modelo="+modelo
							+"&motivo="+motivo
							+"&fecha="+fe
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("La solicitud fue enviada con éxito!", "Da clic en el botón OK!", "success");
						$("input").val("");
						$("textarea").val("");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("Error", "No se pudo enviar la solicitud!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión enviar petición artículo");
				}
			});
		}
	}
	var atenderSolicitud = function()
	{		
		$("#solicitudesPendientes2").hide("slow");
		$("#atenderSolicitud").show("slow");
	}
	var buscarInventario = function()
	{
		if ($("#txtArticuloLista").val() == "") 
		{
			listaArticulos();
		}
	}
	//fin de las peticiones de los articulos
	
	//Reportes
		var resumenReportes=function()
	{

		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#resumenReportes").show("slow");
		alumnosActuales();
	}
	var existenciaInventario=function()
	{
		$("#resumenReportes").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");	
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#existenciaInventario").show("slow");
		$("#btnImprimirInventario").hide();
		resumenInventarioActual();
	}
	var bajoInventario = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#bajoInventario").show("slow");
		$("#btnImprimirBajoInventario").hide();
		articulosDeBaja();
	}
	var enReparacion = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#enReparacion").show("slow");
		$("#btnImprimirEnReparacion").hide();
		articulosEnReparacion();

	}
	var enPrestamo = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#enPrestamo").show("slow");
		$("#btnImprimirEnPrestamo").hide();
		articulosEnPrestamo();

	}
	var pedidoMaterial=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#pedidoMaterial").show("slow");
		$("#btnImprimirPedidoMaterial").hide();
		articulosPedidos();

	}
	var practicasNoRealizadas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#practicasNoRealizadas").show("slow");
		$("#btnImpPracticasNoRealizadas").hide();
		noRealizadas();
	}
	var practicasRealizadas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#practicasRealizadas").show("slow");
		$("#btnImpPracticasRealizadas").hide();
		Realizadas();
	}
	var practicasCanceladas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").show("slow");
		$("#btnImpPracticasCanceladas").hide();
		Canceladas();
	}
	var alumnosActuales = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=alumnosActuales1"+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#alumnosActuales").html("");
					$("#alumnosActuales").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();		
				console.log("Error de conexión");
			}
		});
	}
	var articuloMasPrestado = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=articuloMasPrestado1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#masSolicitado").html(" ");
					$("#masSolicitado").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();	
				console.log("Error de conexión");
			}
		});
	}
	var proximosApartados = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=proximosApartados1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbProximosApartados").html(" ");
					$("#tbProximosApartados").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var resumenInventarioActual = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=resumenInventarioActual1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirInventario").show();
					$("#tbInventarioActual").html(" ");
					$("#tbInventarioActual").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin inventario", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión resumen inventario");
			}
		});
	}
	var articulosDeBaja = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enBaja1"+
						"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirBajoInventario").show();
					$("#tbBajoInventario").html(" ");
					$("#tbBajoInventario").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos dados de baja", "No hay articulos en reparación actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosEnReparacion = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enReparacion1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirEnReparacion").show();
					$("#tbEnReparacion").html(" ");
					$("#tbEnReparacion").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos en reparación", "No hay articulos en reparación actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosEnPrestamo= function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enPrestamo1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirEnPrestamo").show();
					$("#tbArticulosEnPrestamo").html(" ");
					$("#tbArticulosEnPrestamo").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos en préstamo", "No hay articulos en préstamo actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosPedidos= function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enPedido1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirPedidoMaterial").show();
					$("#tbPedidoMaterialReporte").html(" ");
					$("#tbPedidoMaterialReporte").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos pedidos", "No hay articulos en con solicitud de peticion sin aceptar.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosSinExistencia = function()
	{
		$("#loaderImageG").hide();
		var parametros = "opc=articulosSinExistencia1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbMaterialesSinStock").html(" ");
					$("#tbMaterialesSinStock").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}

	var noRealizadas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasNoRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasNoRealizadas").show();
					$("#tbpracticasNoRealizadas").html(" ");
					$("#tbpracticasNoRealizadas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas pendientes actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var Realizadas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasRealizadas").show();
					$("#tbpracticasRealizadas").html(" ");
					$("#tbpracticasRealizadas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas realizadas actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var Canceladas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasCanceladas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasCanceladas").show();
					$("#tbpracticasCanceladas").html(" ");
					$("#tbpracticasCanceladas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas canceladas actualmente.", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	//AGREGUE
	var practicasNR = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasNR1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#practicasNR").html(" ");
					$("#practicasNR").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();	
				console.log("Error de conexión");
			}
		});
		//FIN AGREGUE
	}

	//FIN EDWIN NUEVO
	//Salir
	$("#tabSalir").on("click",salir);
	//Prestamos
	$("#tabPrestamos").on("click",prestamosPendientes);
	$("#btnPendientes").on("click",prestamosPendientes);
	$("#btnEnProceso").on("click",prestamosProceso);
	$("#btnCancelarDevolucion").on("click",prestamosProceso);
	$("#btnListaSanciones").on("click",listaSanciones);
	$("#btnAplicaSancion").on("click",aplicaSancion);
	$("#btnAplicarSancion").on("click",guardaSancionAlumno);
	$("#btnAgregarArtPrestamo").on("click",agregarArticuloPrestamo);
	$("#btnRegresarSancion").on("click",devolucionPrestamo);
	$("#btnDevolucion").on("click",devolucionPrestamo);
	$("#btnFinalizarAtenderSol").on("click",guardarPrestamoPendiente);
	$("#btnCancelarAtenderSol").on("click",prestamosPendientes);
	$("#tabSolPendientesAlumnos").on("click",".eliminarPrestamo",eliminaPrestamoPendiente);
	$("#tbListaArticulosDevolucion").on("click",".devolucionArt",guardarDevolucionPrestamo);
	$("#tbListaArticulosDevolucion").on("click",".aplicaSancion",aplicaSancion);
	$("#btnDevolucionMaterial").on("click",devolucionPrestamo);
	$("#btnFinalizarDevolucion").on("click",prestamosPendientes);
	//Laboratorios
	$("#tabLabs").on("click",sLaboratorioAceptadas);
	$("#btnPendientesLab").on("click",sLaboratorioPendientes);
	$("#btnAceptadasLab").on("click",sLaboratorioAceptadas);
	$("#btnRegresarVerMas").on("click",sLaboratorioPendientes);
	$("#btnRegresarVerMas2").on("click",sLaboratorioAceptadas);
	$("#btnVerMas").on("click",verMas);
	$("#btnVerMas2").on("click",verMas2);
	$("#btnAceptaSolLab").on("click",sGuardaCanderalizada);
	$("#btnCancelarSolLab").on("click",sLaboratorioPendientes);
	$("#btnNuevaLabExtenos").on("click",sLaboratorioNuevas);
	$("#btnElegirMaterialExt").on("click",elegirMatExterno);
	$("#btnRegresarExt").on("click",sLaboratorioNuevas);
	$("#btnAgregarArtExt").on("click",agregarArtExt);
	$("#cmbNombreDependencias").on("change",numeroControlLabExterno);
	$("#chbOtraDependencia").on("change",checkOtraDependencia);
	$("#cmbPracticaExterno").on("change",comboLaboratoriosExt);
	$("#cmbLaboratorioExterno").on("change",comboHoraExt);
	$("#btnFinalizarNSExt").on("click", guardaSolLabExterno);
	//Inventario 
	$("#tabInventario").on("click",listaArticulos);
	$("#btnArticulos").on("click",listaArticulos);
	$("#btnAlta").on("click",altaArticulos);
	$("#btnAltaArt").on("click",altaInventario);
	$("#btnBaja").on("click",bajaArticulos);
	$("#btnBajaArt").on("click",bajaInventario);
	$("#btnBuscarArtBaja").on("click",buscarArticulo);
	$("#btnMantenimiento").on("click",mantenimientoArticulos);
	$("#btnEnviaMtto").on("click",enviaArtMtto);
	$("#btnListaMtto").on("click",listaArtMtto);
	$("#btnBuscarArtMtto").on("click",buscarArticuloMtto);
	$("#btnGuardaMantenimiento").on("click",guardaMtto);
	$("#btnPeticionesPendientes").on("click",peticionesPendientesArt);
	$("#btnPeticionArticulo").on("click",peticionesArticulos);
	$("#btnCancelarPeticionArt").on("click",peticionesArticulos);
	$("#chbOtroArticulo").on("change",checkOtroArticulo);
	$("#btnEnviarPeticionArticulo").on("click",guardaPeticionArticulo);	
	$("#btnBucarInventario").on("click",buscaArtInventario);
	$("#txtArticuloLista").on("change",buscarInventario);
	//Reportes
	$("#tabReportesGenericos").on("click",resumenReportes);
	$("#btnPracticasNoRealizadas").on("click",practicasNoRealizadas);
	$("#btnPracticasRealizadas").on("click",practicasRealizadas);
	$("#btnPracticasCanceladas").on("click",practicasCanceladas);
	$("#btnResumenReportes").on("click",resumenReportes);
	$("#tabReportesGenericos").on("click",alumnosActuales);
	$("#tabReportesGenericos").on("click",articuloMasPrestado);
	$("#tabReportesGenericos").on("click",practicasNR);
	$("#tabReportesGenericos").on("click",articulosSinExistencia);
	$("#tabReportesGenericos").on("click",proximosApartados);
	$("#btnExistenciaInventario").on("click",existenciaInventario);
	$("#btnBajoInventario").on("click",bajoInventario);
	$("#btnMaterialDañado").on("click",enReparacion);
	$("#btnMaterialEnPrestamo").on("click",enPrestamo);
	$("#btnPedidoMaterial").on("click",pedidoMaterial);

	$('.dropdown-button').dropdown({
		inDuration: 300,
		outDuration: 225,
      constrain_width: true, // Does not change width of dropdown to that of the activator
      hover: true, // Activate on hover
      gutter: 0, // Spacing from edge
      belowOrigin: false, // Displays dropdown below the button
      alignment: 'left' // Displays dropdown with edge aligned to the left of button
  });
}
$(document).on("ready",inicioG);