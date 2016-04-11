 //document.write("<script type='text/javascript' src='../js/usuarios.js'></script>");
 var inicio = function()
 {
 	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	$('.collapsible').collapsible({
      accordion : false}); // A setting that changes the collapsible behavior to expandable instead of the default accordion style

	var articulosPrestados = new Array();
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
					url:"../data/genericos.php",
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
				swal("OK..!","Aún sigues en el sistema", "error");
			} 
		}
		);
	}
	//Prestamos de matertial a alumnos y externos
	var prestamosPendientes = function()
	{
		$("#atenderSolicitud").hide("slow");
		$("#alumnosSancionados").hide("slow");
		$("#solicitudesEnProceso").hide("slow");
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
					$("#tabSolPendientesAlumnos").html("");
					$("#tabSolPendientesAlumnos").html(response.renglones);
					$("#tabSolPendientesAlumnos #btnAtenderPrestamo").on("click",atenderPrestamoMaterial);
					//$("#tabSolPendientesAlumnos #btnEliminarprestamo").on("click",verMas);
				}
				else
				{
					sweetAlert("No hay prestamos pendientes!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión prestamos pendientes");
			}
		});
		$("#solicitudesPendientes").show("slow");
		$("#solicitudesPendientes2").show("slow");
	}
	var atenderPrestamoMaterial = function()
	{
		articulosPrestados = Array();
		$("#solicitudesPendientes2").hide("slow");
		var clavePrestamo= $(this).attr('name');
		$(this).closest("tr").remove()
		var parametros 	= "opc=atenderPrestamo1"+"&clavePrestamo="+clavePrestamo+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#txtcodigoBarrasPrestamo").val("");
					$("#tbListaMaterialPrestamo").html("");
					$("#bodyArtSolicitados").html("");
					$("#tbListaMaterialPrestamo").html(response.renglones);
					$("#txtnombreAlumnoPrestamo").val(response.nombre);
				}
				else
				{
					sweetAlert("No se pudo atender la solicitud!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión atender prestamo material");
			}
		});
		$("#atenderSolicitud").show("slow");
		$("#atenderSolicitud2").show("slow");

	}
	var agregarArticuloPrestamo = function()
	{
		if(($("#txtcodigoBarrasPrestamo").val())!="")
		{
			var identificadorArticulo= $("#txtcodigoBarrasPrestamo").val();
			var parametros= "opc=agregaArticulos1"
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
						$("#txtcodigoBarrasPrestamo").val("");
						$("#tbArticulosSolicitados > tbody").append("<tr><td>"+response.idu+"</td><td>"+response.nomArt+"</td></tr>");
						articulosPrestados.push(response.idu);
					}
					else
					{
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión al buscar el articulo para agregarlo", "error");
				}
			});
		}
	}
	var guardarPrestamoPendiente = function()
	{
		var listaArt 		= articulosPrestados;
		var clavePrestamo 	= $("#btnAtenderPrestamo").attr('name');
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
						swal("Prestamo finalizado con éxito!", "Da clic en el botón OK!", "success");
						prestamosPendientes();
					}
					else
					{
						sweetAlert("Error", "No se pudo finalizar el prestamo!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión guarda prestamo pendiente", "error");
				}
			});
	}
	var eliminaPrestamoPendiente = function()
	{
		var clavePrestamo 	= $("#btnEliminarPrestamo").attr('name');
		$(this).closest("tr").remove();
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
						swal("Prestamo eliminado con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						sweetAlert("Error", "No se pudo eliminar el prestamo!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión elimina prestamo pendiente", "error");
				}
			});
	}
	var prestamosProceso = function()
	{
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#alumnosSancionados").hide("slow");
		var parametros 	= "opc=prestamosProceso1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tabSolProcesoAlumnos").html("");
					$("#tabSolProcesoAlumnos").html(response.renglones);
					$("#tabSolProcesoAlumnos #btnDevolucionMaterial").on("click",devolucionPrestamo);
					//$("#tabSolPendientesAlumnos #btnEliminarprestamo").on("click",verMas);
				}
				else
				{
					sweetAlert("No hay prestamos en proceso!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión prestamos en proceso");
			}
		});
		$("#solicitudesEnProceso").show("slow");
		$("#solicitudesEnProceso2").show("slow");
	}
	var listaSanciones = function()
	{	
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#solicitudesEnProceso").hide("slow");
		var parametros 	= "opc=listaSanciones1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tabListaSanciones").html(response.renglones);
					$("#tabListaSanciones #btnQuitaSancion").on("click",quitaSancion);
				}
				else
				{
					sweetAlert("No hay personas sancionadas!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión lista de sanciones");
			}
		});
		$("#alumnosSancionados").show("slow");
		$("#listaSanciones2").show("slow");
	}
	var quitaSancion = function ()
	{
		var parametros 	= "opc=quitaSanciones1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					alert("muy bien :)");
				}
				else
				{
					sweetAlert("la sanción no se pudo eliminar!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión quitar sanción");
			}
		});
	}
	var aplicaSancion = function()
	{		
		$("#devolucionMaterial2").hide("slow");
		var parametros 	= "opc=aplicaSancion1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					alert("muy bien :)");
				}
				else
				{
					sweetAlert("No se pudo aplicar la sanción!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión aplica sanción");
			}
		});
		$("#aplicaSanciones").show("slow");
	}
	var guardaSancionAlumno = function()
	{
		var parametros 	= "opc=guardaSancion1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					alert("muy bien :)");
				}
				else
				{
					sweetAlert("No se pudo aplicar la sanción!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión al guardar la sanción");
			}
		});
	}
	var devolucionPrestamo = function()
	{
		$("#solicitudesEnProceso2").hide("slow");
		$("#aplicaSanciones").hide("slow");
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
					$("#tbListaArticulosDevolucion").html(response.renglones);
					$("#txtClavePrestamoDevolucion").val(response.clavePrestamo);
					//$("#tbListaArticulosDevolucion #btnDevolverArt").on("click",aceptarSolicitudLab);
					//$("#tbListaArticulosDevolucion #btnAplicaSancion").on("click",aceptarSolicitudLab);
				}
				else
				{
					sweetAlert("No existe el prestamo!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión devolución prestamo");
			}
		});
		$("#devolucionMaterial").show("slow");
		$("#devolucionMaterial2").show("slow");
	}
	var guardarDevolucionPrestamo = function()
	{
		var horaActual 				= new Date();
		var fechaActual				= new Date();
		var hora 					=horaActual.getHours();
		var minutos 				=horaActual.getMinutes();
		var dia 					=fechaActual.getDate();
		var mes 					=fechaActual.getMonth()+1;
		var anno 					=fechaActual.getFullYear();
		var horaDevolucion			= hora + ":" + minutos;
		var fechaDevolucion 		= dia + "/" + mes + "/" + anno;

		var identificadorArticulo 	= $(this).attr('name');
		var clavePrestamo 			= $("#txtClavePrestamoDevolucion").val();
		$(this).closest("tr").remove();
		var parametros 		= "opc=guardaDevolucion1"
							+"&clavePrestamo="+clavePrestamo
							+"&identificadorArticulo="+identificadorArticulo
							+"&horaDevolucion="+horaDevolucion
							+"&fechaDevolucion="+fechaDevolucion
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
						swal("Devolución guardada con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						sweetAlert("Error", "No se pudo guardar la devolución!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión guarda prestamo devolución", "error");
				}
			});
	}
	//Laboratorios
	//solicitudes pendientes de laboratorio...
	//funcion para aceptar una solicitud, introduciendo datos fltantes para agendarla
	var aceptarSolicitudLab = function()
	{
		$("#verPrincipal").hide("slow");
		$("#solicitudesPendientesLab2").hide("slow");
		var claveSol= $(this).attr('name');
		var parametros 	= "opc=obtenerDatosSolLab1"+"&clave="+claveSol+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#txtFechaAsignada").val(response.fecha);
					$("#txtHoraAsignada").val(response.hora);
					$("#txtClaveSol").val(claveSol);
				}
				else
				{
					sweetAlert("Lasolicitud no existe!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión aceptar solicitud de laboratorio");
			}
		});
 		$("#aceptarSolLab").show("slow");
		$("#guardarSolicitud").show("slow");
		$("#verMasSolicitud").show("slow");

	}
	//funcion para guardar una solicitud de laboratorio
	var sGuardaCanderalizada = function(){
		var claveSol	 = $("#txtClaveSol").val();
		var fechaAsignada= $("#txtFechaAsignada").val();
		var horaAsignada = $("#txtHoraAsignada").val();
		var firmaJefe 	 = $("#txtFirmaJefe").val();
		var comentarios  = $("#txtComentariosSol").val();
		var estatus= "NR";
		var claveCal= "";
		if (($("#txtFirmaJefe").val())!="" && ($("#txtClaveSol").val())!="" && ($("#txtComentariosSol").val())!="") 
		{
			var parametros 	= "opc=guardaSolicitudLab1"
								+"&clave="+claveSol
								+"&estatus="+estatus
								+"&claveCal="+claveCal
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
						sweetAlert("La solicitud fue calendarizada con éxito!", "Da click en el botón OK", "success");
					}
					else
						sweetAlert("La solicitud no se calendarizó!", " ", "error");
				},
				error: function(xhr, ajaxOptions,x){
					alert("Error de conexión guarda solicitud laboratorio");
				}
			});
		}
	}
	//funcion para eliminar una solicitud de laboratorio
	var eliminarSolLab = function(){
		var claveSol= $(this).attr('name');
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
					sweetAlert("La solicitud fue eliminada con éxito!", "Da click en el botón OK", "success");
				}
				else
					sweetAlert("No se pudo eliminar la solicitud!", " ", "error");
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión elimina solicitud de laboratorio");
			}
		});
	}
	var sLaboratorioPendientes = function()
	{
		$("#sAceptadasLab").hide("slow");
		$("#verMasSolicitud").hide("slow");
		$("#aceptarSolLab").hide("slow");
		$("#guardarSolicitud").hide("slow");
		var parametros 	= "opc=pendientesLab1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbPendientesLab").html("");
					$("#tbPendientesLab").html(response.renglones);
					$("#tbPendientesLab #btnCalendarizado").on("click",aceptarSolicitudLab);
					$("#tbPendientesLab #btnVerMas").on("click",verMas);
					$("#tbPendientesLab #btnEliminarSolLab").on("click",eliminarSolLab);
				}
				else
					sweetAlert("No hay solicitudes de laboratorio pendientes!", " ", "error");
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión solicitudes pendientes de laboratorio");
			}
		});
		$("#sPendientesLab").show("slow");
		$("#solicitudesPendientesLab2").show("slow");
	}
	var sLaboratorioAceptadas = function()
	{
		$("#sPendientesLab").hide("slow");
		$("#verMasSolicitud2").hide("slow");
		var claveCal = $(this).attr('name');
		var parametros 	= "opc=aceptadasLab1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbAceptadasLab").html("");
					$("#tbAceptadasLab").html(response.renglones);
					$("#tbAceptadasLab a").on("click",verMas2);
				}
				else
					sweetAlert("No hay solicitudes de laboratorio aceptadas!", " ", "error");
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión solicitudes aceptadas de laboratorio");
			}
		});
		$("#sAceptadasLab").show("slow");
		$("#solicitudesAceptadasLab2").show("slow");
	}
	var verMas = function()
	{		
		$("#solicitudesPendientesLab2").hide("slow");
		//contenido dinamico
		var realid = $(this).attr("name");
		var parametros = "opc=verMasLab1"
						+"&clave="+realid
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
					$("#txtFecha1").val(response.fecha);
					$("#txtHora1").val(response.hora);
					$("#txtMaestro1").val(response.maestro);
					$("#txtPractica1").val(response.practica);
					$("#tbMaterialesPendientesLab").html(response.renglones);
				}
				else
				{
					sweetAlert("No existe esa solicitud..!", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				alert("Error de conexión realizadas");
			}
		});	
		$("#verMasSolicitud").show("slow");
	}
	var verMas2 = function()
	{		
		$("#solicitudesAceptadasLab2").hide("slow");
		//contenido dinamico
		var claveCal = $(this).attr("name");
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
					$("#txtFecha2").val(response.fechaAsignada);
					$("#txtHora2").val(response.horaAsignada);
					$("#txtMaestro2").val(response.maestro);
					$("#txtPractica2").val(response.practica);
					$("#tbMaterialesAceptadasLab").html(response.renglones);
				}
				else
				{
					sweetAlert("No existe esa solicitud..!", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				alert("Error de conexión realizadas");
			}
		});	
		$("#verMasSolicitud2").show("slow");
	}
	//Inventario
	var listaArticulos = function()
	{
		$("#altaArticulos").hide("slow");
		$("#bajaArticulos").hide("slow");
		$("#menuMtto").hide("slow");
		$("#editar").hide("slow");
		$("#peticionesPendientes").hide("slow");
		$("#peticionesArticulos").hide("slow");
		var parametros 	= "opc=listaArticulos1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbInventario").html("");
					$("#tbInventario").html(response.renglones);
				}
				else
					sweetAlert("No hay artículos..!", " ", "error");
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión lista de artículos");
				console.log(xhr);	
			}
		});
		$("#listaArt").show("slow");
		$("#pantallaInventario").show("slow");
	}
	var altaArticulos = function()
	{
		$("#pantallaInventario").hide("slow");
		$("#bajaArticulos").hide("slow");
		$("#menuMtto").hide("slow");
		$("#peticionesPendientes").hide("slow");
		$("#peticionesArticulos").hide("slow");
		$("input").val("");
		$("textarea").val("");
		$("#altaArticulos").show("slow");
	}
	var altaInventario = function()
	{
		if(($("#txtCodigoBarrasAlta").val())!="" && 
			($("#txtModeloArtAlta").val())!="")
		{
			//aqui empieza todo
       		//var cveUsuario = usuarioNombre();
       		var imagen						= " ";
       		var identificadorArticulo		= $("#txtCodigoBarrasAlta").val();
       		var modelo 						= $("#txtModeloArtAlta").val();
       		var numeroSerie 				= $("#txtNumSerieAlta").val();
			var claveArticulo				= $("#cmbNombreArt").val();//ocupo sacar el valor del select
			var marca						= $("#txtMarcaArtAlta").val();
			var tipoContenedor 				= $("#txtTipoContenedorAlta").val();
			var descripcionArticulo			= $("#txtDescripcionArtAlta").val();
			var descripcionUso				= $("#txtDescripcionUsoAlta").val();
			var unidadMedida 				= $("#cmbUm").val();
			var fechaCaducidad				= $("#txtFechaCaducidadAlta").val();
			var claveKit					= $("#txtClaveKitAlta").val();
			var ubicacionAsignada			= $("#txtUbicacionAlta").val();
			var estatus						= "V";
			var parametros 	= "opc=altaInventario1"+"&claveArticulo="+claveArticulo
								+"&imagen="+imagen
								+"&identificadorArticulo="+identificadorArticulo
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
				success: function(response){
					if(response.respuesta == true)
					{
						swal("El articulo fue dado de alta con éxito!", "Da clic en el botón OK!", "success");
						$("input").val("");
						$("textarea").val("");
					}
					else
					{
						sweetAlert("Error", "No se pudo insertar el articulo!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión alta inventario", "error");
				}
			});
		}
	}
	//muestra la pantalla de baja articulos
	var bajaArticulos = function()
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
	//da de baja un articulo al presionar el boton dar de baja
	var bajaInventario = function()
	{
		if($("#cmbTipoBaja").val()!="" && $("#txtMotivoDeBaja").val()!="" && $("#txtModeloArtBaja").val()!="")
		{
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
						swal("El articulo fue dado de baja con éxito!", "Da clic en el botón OK!", "success");
						$("input").val("");
						$("textarea").val("");
					}
					else
					{
						sweetAlert("Error", "No se pudo dar de baja el articulo!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión baja de artículos", "error");
				}
			});
		}
		else
		{
			sweetAlert("Error", "Por favor llena todos los campos!", "error");
		}
	}
	//buscar el articulo a dar de baja y regresa todos sus datos y los muestra 
	//en la pantalla de dar de baja, rellenando los campos
	var buscarArticulo = function() 
	{
		if(($("#txtCodigoBarrasBaja").val())!="")
		{
			var identificadorArticulo= $("#txtCodigoBarrasBaja").val();
			var parametros= "opc=buscaArticulos1"
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
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión al buscar el articulo para darlo de baja", "error");
				}
			});
		}
	}
	//Muestra la pantalla de enviar articulos a mantenimiento con sus dos botones
	var mantenimientoArticulos = function()
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
	//Pantalla para enviar articulos a mantenimiento(solicitud)
	var enviaArtMtto = function()
	{
		$("#listaArtMtto").hide("slow");
		$("#listaMtto").hide("slow");
		$("input").val("");
		$("textarea").val("");
		$("#menuMtto").show("slow");
		$("#sEnvioMtto").show("slow");
	}
	//Pantalla para visualizar que articulos fueron enviados a mantenimiento
	var listaArtMtto = function()
	{
		$("#sEnvioMtto").hide("slow");
		var parametros 	= "opc=listaMantenimiento1"+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#tbArticulosMtto").html("");
					$("#tbArticulosMtto").html(response.renglones);
				}
				else
				{
					sweetAlert("No hay articulos en mantenimiento!", " ", "error");
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión lista de articulos en mantenimiento");
			}
		});
		$("#listaArtMtto").show("slow");
		$("#listaMtto").show("slow");
		$("#menuMtto").show("slow");
	}
	//Busca el articulo que queremos enviar a mantenimiento
	var buscarArticuloMtto = function() 
	{
		if(($("#txtCodigoBarrasMtto").val())!="")
		{
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
						$("#txtModeloArtMtto").val(response.modelo);
						$("#txtNumSerieMtto").val(response.numeroSerie);
						$("#txtNombreArtMtto").val(response.nombreArticulo);
						$("#txtMarcaArtMtto").val(response.marca);
						$("#txtFechaCaducidadMtto").val(response.fechaCaducidad);
					}
					else
					{
						sweetAlert("Error", "El artículo no existe", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión al buscar el artículo para registrar el mantenimiento", "error");
				}
			});
		}
	}
	//Cambia el estatus del articulo a M que es mantenimiento
	var guardaMtto = function()
	{
		if(($("#txtCodigoBarrasMtto").val())!="" && ($("#txtLugarReparacionMtto").val())!=""
			&& ($("#txtMotivoMtto").val())!="")
		{
			var horaActual 				= new Date();
			var fechaActual				= new Date();
			var hora 					=horaActual.getHours();
			var minutos 				=horaActual.getMinutes();
			var dia 					=fechaActual.getDate();
			var mes 					=fechaActual.getMonth()+1;
			var anno 					=fechaActual.getFullYear();

			var identificadorArticulo	= $("#txtCodigoBarrasMtto").val();//obtener el articulo a dar de baja
			var observaciones 			= $("#txtMotivoMtto").val()
			var periodo					= "67236";
			var estatus					= "M";
			var claveMovimiento			= " ";
			var claveLab				= "1";
			var horaMovimiento			= hora + ":" + minutos;
			var fechaMovimiento 		= dia + "/" + mes + "/" + anno;
			var respons 				= "1"
			var parametros 	= "opc=mantenimientoArticulos1"
							+"&identificadorArticulo="+identificadorArticulo
							+"&observaciones="+observaciones
							+"&horaMovimiento="+horaMovimiento
							+"&fechaMovimiento="+fechaMovimiento
							+"&periodo="+periodo
							+"&estatus="+estatus
							+"&claveMovimiento="+claveMovimiento
							+"&claveLab="+claveLab
							+"&respons="+respons
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
						swal("El envío a mantenimiento quedó registrado!", "Da clic en el botón OK!", "success");
						$("input").val("");
						$("textarea").val("");
					}
					else
					{
						sweetAlert("Error", "No se pudo registrar el envío a mentenimiento!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión al registrar el mantenimiento del artículo", "error");
				}
			});
		}
		else
		{
			sweetAlert("Error", "Llene todos los campos", "error");
		}
	}
	//Inventario
	//Peticiones de articulos
	var peticionesPendientesArt = function()
	{
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
					$("#tbPeticionArticulos").html("");
					$("#tbPeticionArticulos").html(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión peticiones pendientes");
			}
		});
		$("#peticionesPendientes").show("slow");
	}
	var peticionesArticulos = function()
	{
		$("#altaArticulos").hide("slow");
		$("#bajaArticulos").hide("slow");
		$("#menuMtto").hide("slow");
		$("#pantallaInventario").hide("slow");
		$("#peticionesPendientes").hide("slow");
		$("#peticionesArticulos").show("slow");
		$("input").val("");
		$("textarea").val("");
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
		var nombreArticulo;
		if ($("#chbOtroArticulo").is(':checked'))
		{
			nombreArticulo = $("#txtNombreArticuloPeticion").val();
		}
		else
		{
			nombreArticulo = $("#cmbNombreArtPeticiones").val();
		}
			var fechaActual				= new Date();
			var dia 					=fechaActual.getDate();
			var mes 					=fechaActual.getMonth()+1;
			var anno 					=fechaActual.getFullYear();

			var cantidad 	= $("#txtCantidadPeticionArt").val();
			var marca 		= $("#txtMarcaPeticionArt").val();
			var modelo 		= $("#txtModeloPeticionArt").val();
			var motivo 		= $("#txtMotivoPedidoArt").val();
			var fecha 		= dia + "/" + mes + "/" + anno;

			var parametros= "opc=guardaPeticionArticulos1"
							+"&nombreArticulo="+nombreArticulo
							+"&cantidad="+cantidad
							+"&marca="+marca
							+"&modelo="+modelo
							+"&motivo="+motivo
							+"&fecha="+fecha
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
						swal("La solicitud fue enviada con éxito!", "Da clic en el botón OK!", "success");
						$("input").val("");
						$("textarea").val("");
					}
					else
					{
						sweetAlert("Error", "No se pudo enviar la solicitud!", "error");
					}
				},
				error: function(xhr, ajaxOptions,x){
					sweetAlert("Error", "Error de conexión enviar petición artículo", "error");
				}
			});
	}
	var atenderSolicitud = function()
	{		
		$("#solicitudesPendientes2").hide("slow");
		$("#atenderSolicitud").show("slow");
	}
	//fin de las peticiones de los articulos
	/*var editarArticulo = function()
	{		
		$("#listaArt").hide("slow");
		$("#editar").show("slow");
	}*/
	//Reportes
	var resumenReportes=function()
	{
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#resumenReportes").show("slow");
	}
	var existenciaInventario=function()
	{
		$("#resumenReportes").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");	
		$("#existenciaInventario").show("slow");
	}
	var bajoInventario = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").show("slow");
	}
	var pedidoMaterial=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#pedidoMaterial").show("slow");
	}
	//Salir
	$("#tabSalir").on("click",salir);
	//Prestamos
	$("#tabPrestamos").on("click",prestamosPendientes);
	$("#btnPendientes").on("click",prestamosPendientes);
	$("#btnEnProceso").on("click",prestamosProceso);
	$("#btnCancelarDevolucion").on("click",prestamosProceso);
	$("#btnListaSanciones").on("click",listaSanciones);
	$("#btnAplicaSancion").on("click",aplicaSancion);
	$("#btnAgregarArtPrestamo").on("click",agregarArticuloPrestamo);
	$("#btnRegresarSancion").on("click",devolucionPrestamo);
	$("#btnDevolucion").on("click",devolucionPrestamo);
	$("#btnFinalizarAtenderSol").on("click",guardarPrestamoPendiente);
	$("#btnCancelarAtenderSol").on("click",prestamosPendientes);
	$("#tabSolPendientesAlumnos").on("click",".eliminarPrestamo",eliminaPrestamoPendiente);
	$("#tbListaArticulosDevolucion").on("click",".devolucionArt",guardarDevolucionPrestamo);
	$("#btnDevolucionMaterial").on("click",devolucionPrestamo);
	$("#btnFinalizarDevolucion").on("click",prestamosPendientes);
	//Laboratorios
	$("#tabLabs").on("click",sLaboratorioPendientes);
	$("#btnPendientesLab").on("click",sLaboratorioPendientes);
	$("#btnAceptadasLab").on("click",sLaboratorioAceptadas);
	$("#btnRegresarVerMas").on("click",sLaboratorioPendientes);
	$("#btnRegresarVerMas2").on("click",sLaboratorioAceptadas);
	$("#btnVerMas").on("click",verMas);
	$("#btnVerMas2").on("click",verMas2);
	$("#btnAceptaSolLab").on("click",sGuardaCanderalizada);
	$("#btnCancelarSolLab").on("click",sLaboratorioPendientes);
	//Inventario **solo faltan las peticiones
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
	
	//Reportes
	$("#tabReportesGenericos").on("click",resumenReportes);
	$("#btnResumenReportes").on("click",resumenReportes);
	$("#btnExistenciaInventario").on("click",existenciaInventario);
	$("#btnBajoInventario").on("click",bajoInventario);
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
$(document).on("ready",inicio);