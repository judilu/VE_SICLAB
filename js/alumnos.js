var inicio = function()
{
	$('select').material_select();
	var articulosSolicitadosAlumnos = new Array();
	var numeroArticulos = new Array();
	var nombreArticulos = new Array();
	var practicaAlumnos = function()
	{
		if(($("#txtNControl").val())!=" " && ($("#txtNombre").val())!=" ") 
		{
			$("#accesoAlumno").hide();
			$("#datosPracticas").show("slow");
			var numeroControl = $("#txtNControl").val();
			$("#txtNControlAlu").val(numeroControl.substr(0,8));
			var numCtrl 	= $("#txtNControlAlu").val();
			$("#txtNombreAlu").val($("#txtNombre").val());
			var parametros = "opc=consultaMatAlumno"+
			"&numeroControl="+numCtrl+
			"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/alumnos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
	       				$("#cmbMateriasAlumnos").html(" ");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbMateriasAlumnos").append($("<option></option>").attr("value",response.claveMateria[i]).text(response.nombreMateria[i]));
						}
						$("#cmbMateriasAlumnos").trigger('contentChanged');
						$('select').material_select();
	   				}
	   				else
	   				{
	   					sweetAlert("No hay materias disponibles", "", "error");
	   				}
	   			},
	   			error: function(xhr, ajaxOptions,x)
	   			{
	   				console.log("Error de conexión datos practica");
	   			}
	   		});
		}
		else
		{
			sweetAlert("Número de contro incorrecto", "", "error");
		}
	}
	var materialPractica = function()
	{
		articulosSolicitadosAlumnos = new Array();
		nombreArticulos = new Array();
		numeroArticulos = new Array();
		articulosSolicitadosAlumnos1 = new Array();
		nombreArticulos1 = new Array();
		numeroArticulos1 = new Array();
		$("#datosPractica2").hide();
		$("#materialAlumno").show("slow");
		$("#txtNumeroControlPrestamo").val($("#txtNControlAlu").val());
		$("#txtNombreAluPrestamo").val($("#txtNombreAlu").val());
		var nc 		=$("#txtNumeroControlPrestamo").val();
		var claveCal = $("#cmbHorariosPractica").val();
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
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var hora					= hora + ":" + minutos;
		var parametros = "opc=consultaMaterialPractica1"+
							"&clave="+claveCal+
							"&fecha="+fe+
							"&hora="+hora+
							"&nC="+nc+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#bodyArtAlumno").append(response.renglones);
					for (var i = 0; i < response.contador; i++) {
						articulosSolicitadosAlumnos.push(response.materiales[i]);
						numeroArticulos.push(response.cantidad[i]);
						nombreArticulos.push(response.nombre[i]);
					}
					alert(articulosSolicitadosAlumnos);
				}
				else
				{
					sweetAlert("No hay material asignado para la práctica", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión");
				console.log(xhr);
			}
		});

	}
	var uno= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+1);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}

	}
	var dos= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+2);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var tres= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+3);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var cuatro= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+4);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var cinco= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+5);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var seis= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+6);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var siete= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+7);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var ocho= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+8);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var nueve= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+9);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);
		}
	}
	var cero= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+0);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			consultaAlumno(str);

		}
	}
	var ma= function()
	{
		if($("#txtNControl").val()=="")
			$("#txtNControl").val("MA");
		else
		{
			sweetAlert("Error", "El prefijo MA solo puede ir al inicio.", "error");
		}
	}
	var del= function()
	{
		var objEntrada = document.getElementById('txtNControl');
		if(objEntrada.value.length > 0 && objEntrada.value!="MA")
			objEntrada.value = objEntrada.value.substring(0,objEntrada.value.length-1);
		else
		{
			if(objEntrada.value=="MA")
				objEntrada.value = objEntrada.value.substring(objEntrada.value.length);
		}
	}

	//FUNCIONES PHP
	var consultaAlumno = function(str)
	{
		var parametros = "opc=consultaAlumno"+
		"&nControl="+str+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					document.getElementById('txtNombre').focus();
					$("#txtNombre").val(response.ALUAPP+" "+ response.ALUAPM+" "+response.ALUNOM);	
					consultaCarrera(str);
				}
				else
				{
					sweetAlert("NO ENCONTRADO", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión");
			}
		});
	}

	var consultaCarrera = function(str)
	{
		var parametros = "opc=consultaCarrera"+
		"&nControl="+str+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					document.getElementById('txtCarrera').focus();
					$("#txtCarrera").val(response.CARNOM);
					document.getElementById('txtSemestre').focus();	
					$("#txtSemestre").val(response.CALNPE);
					$("#txtNControl").attr("disabled","disabled");
				}
				else
				{
					sweetAlert("No tienes materias", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión");
			}
		});
	}
	var maestroPractica = function()
	{
		var claveMateria = $("#cmbMateriasAlumnos").val();
		var parametros = "opc=consultaMaestro"+
		"&claveMateria="+claveMateria+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#cmbMaestrosMat").html(" ");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbMaestrosMat").append($("<option></option>").attr("value",response.claveMaestro[i]).text(response.nombreMaestro[i]));
						}
						$("#cmbMaestrosMat").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					sweetAlert("Maestro incorrecto", "", "error");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				console.log("Error de conexión");
   			}
   		});
	}
	var nombrePracticaMaestro = function()
	{
		var horaPrac 	= $("#cmbHorariosPractica").val();
		var parametros 	= "opc=consultaPracticaNombre"+
							"&horaPrac="+horaPrac+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#cmbNombrePracticas").html(" ");
						$("#cmbNombrePracticas").append($("<option></option>").attr("value",response.clavePractica).text(response.nombrePractica));
						$("#cmbNombrePracticas").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					sweetAlert("No hay practicas asignadas a esa hora", "", "error");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				console.log("Error de conexión");
   			}
   		});
	}
	var horarioPractica = function()
	{
		//fecha del sistema
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());
		var claveMaestro = $("#cmbMaestrosMat").val();
		var parametros 		= "opc=consultaHoraPractica"+
								"&claveMaestro="+claveMaestro+
								"&fecha="+fe+
								"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#cmbHorariosPractica").html(" ");
						$("#cmbHorariosPractica").append($("<option></option>").attr("value",response.clavePractica).text(response.horaPractica));
						$("#cmbHorariosPractica").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					sweetAlert("EL maestro no tiene asignadas prácticas", "", "error");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				console.log("Error de conexión");
   			}
   		});
	}
	var guardaEntradaAlumno = function()
	{
		var nc   		= $("#txtNControlAlu").val();
		var claveCal 	= $("#cmbHorariosPractica").val();
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
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var horaEntrada			= hora + ":" + minutos;
		var parametros 	= "opc=guardaEntrada1"+
						"&claveCal="+claveCal+
						"&nControl="+nc+
						"&fecha="+fe+
						"&hora="+horaEntrada+
						"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					sweetAlert("Registro de entrada guardado con éxito!", "Da click en el botón OK", "success");
   					if($("#chbElegirMaterial").is(':checked'))
   					{
   						materialPractica();
   					}
   				}
   				else
   				{
   					sweetAlert("No se registró la entrada", "", "error");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				console.log("Error de conexión registro de entrada alumnos");
   			}
   		});
	}
	var cancelaEntrada = function ()
	{
		$("#txtNControl").val(" ");
		$("#txtNombre").val(" ");
		$("#txtCarrera").val(" ");
		$("#txtSemestre").val(" ");
		$("#txtNControl").removeAttr("disabled");
		del();

		//$("#alumno").show("slow");
		//$("#accesoAlumno").show("slow");
	}
	var agregaArtAlumno = function()
	{
		var articuloCve = $("#cmbMaterialesLab" ).val();
		var artNom 		= $("#cmbMaterialesLab option:selected").text();
    	var numArt    	= $("#txtNumArtMat").val();
    	nombreArticulos.push(artNom);
		numeroArticulos.push(numArt);
    	articulosSolicitadosAlumnos.push(articuloCve); 	
    	var parametros = "opc=agregarArtAlu1"+
    						"&artNom="+artNom+
    						"&artCve="+articuloCve+
    						"&numArt="+numArt+
    						"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{		    	
    				$("#txtNumArtMat").val("1");
    				$("#bodyArtAlumno").append(response.renglones);
					$("#bodyArtAlumno #btnEliminarArtAlu").on("click",eliminarArtAlumno);
				}
				else
					sweetAlert("No se agregó el articulo", "", "error");
			},
			error: function(xhr, ajaxOptions,x){
				console.log("Error de conexión articulo agregado");	
			}
		});
	}
	var checkOtroArticulo = function()
	{
		var claveCal = $("#cmbHorariosPractica").val();
		if ($("#chbElegirOtroMaterial").is(':checked'))
		{
			$(".select-dropdown").removeAttr("disabled");
			$("#cmbMaterialesLab").removeAttr("disabled");
			$("#txtNumArtMat").removeAttr("disabled");
			$("#btnAgregarArtAlu").show();
			var parametros = "opc=materialesDisponibles1"+
							"&claveCal="+claveCal+
    						"&id="+Math.random();
			$.ajax({
	    		cache:false,
	    		type: "POST",
	    		dataType: "json",
	    		url:"../data/alumnos.php",
	    		data: parametros,
	    		success: function(response){
	    			if(response.respuesta == true)
	    			{
	    				$("#cmbMaterialesLab").html(" ");
							for (var i = 0; i < response.contador; i++) 
							{
								$("#cmbMaterialesLab").append($("<option></option>").attr("value",response.claveArticulo[i]).text(response.nombreArticulo[i]));
							}
							$("#cmbMaterialesLab").trigger('contentChanged');
							$('select').material_select();
					}
					else
					{
						sweetAlert("No hay hay mas articulos en el laboratorio", "", "error");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					console.log("Error de conexión");
					console.log(xhr)	;
				}
			});
		}
		else
		{
			$("#btnAgregarArtAlu").hide();
			$("#txtNumArtMat").attr("disabled","disabled");
			$(".select-dropdown").attr("disabled","disabled");
			$("#cmbMaterialesLab").attr("disabled","disabled");
		}
	}
	var buscaIndice = function(arreglo,elemento)
	{
		var ar= arreglo;
		var e=elemento;
		var c=elemento.length;
		for (var i = 0; i < c; i++) 
		{
			if (ar[i]==e) 
			{
				return i;
			}
		}
	}
	var eliminarArtAlumno = function()
	{
    	var artEliminar = ($(this).attr('name'));
    	var i = buscaIndice(articulosSolicitadosAlumnos,artEliminar);
    	alert(i);
    	nombreArticulos = eliminar2(nombreArticulos,i);
    	articulosSolicitadosAlumnos = eliminar2(articulosSolicitadosAlumnos,i);
    	numeroArticulos = eliminar2(numeroArticulos,i);
    	var parametros = "opc=construirTablaArt1"+
				    	"&articulosSolicitados="+articulosSolicitadosAlumnos+
				    	"&nombreArticulos="+nombreArticulos+
				    	"&numeroArticulos="+numeroArticulos+
				    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#bodyArtAlumno").html("");
    				$("#bodyArtAlumno").append(response.renglones);
    				$("#bodyArtAlumno #btnEliminarArtAlu").on("click",eliminarArtAlumno);
					//formar de nuevo el combo
					checkOtroArticulo();
				}//termina if
				else
				{
					sweetAlert("No se eliminó el articulo", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión articulo eliminar");	
				console.log(xhr);
			}
		});
	}
	var eliminar2 = function(arreglo,posicion)
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
	var guardaSolMaterial = function ()
	{
		var listaArt 		= articulosSolicitadosAlumnos;
		var cantArt 		= numeroArticulos;
		var nc 				=$("#txtNumeroControlPrestamo").val();
		var claveCal 		= $("#cmbHorariosPractica").val();
		var parametros = "opc=guardaSolicitudAlu"+
							"&listaArt="+listaArt+
							"&cantArt="+cantArt+
							"&claveCal="+claveCal+
							"&numC="+nc+
    						"&id="+Math.random();
		$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				swal("Solicitud enviada con éxito!", "Da clic en el botón OK!", "success");
				}
				else
				{
					sweetAlert("No hay hay mas articulos en el laboratorio", "", "error");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión");
				console.log(xhr)	;
			}
		});

	}

	$("#btnPracticaAlumnos").on("click",practicaAlumnos);
	$("#btnMaterialAlumno").on("click",materialPractica);

	//Botones teclado numerico
	$("#btn1").on("click",uno);
	$("#btn2").on("click",dos);
	$("#btn3").on("click",tres);
	$("#btn4").on("click",cuatro);
	$("#btn5").on("click",cinco);
	$("#btn6").on("click",seis);
	$("#btn7").on("click",siete);
	$("#btn8").on("click",ocho);
	$("#btn9").on("click",nueve);
	$("#btn0").on("click",cero);
	$("#btnMA").on("click",ma);
	$("#btnDel").on("click",del);
	//selects
	$("#chbElegirOtroMaterial").on("change",checkOtroArticulo);
	$("#cmbMateriasAlumnos").on("change",maestroPractica);
	$("#cmbMaestrosMat").on("change",horarioPractica);
	$("#cmbHorariosPractica").on("change",nombrePracticaMaestro );
	$("#btnEntradaAlumno").on("click",guardaEntradaAlumno);
	$("#btnCancelarEntrada").on("click",cancelaEntrada);
	$("#btnAgregarArtAlu").on("click",agregaArtAlumno);
	$("#btnAceptarEleccionMat").on("click",guardaSolMaterial);
}
$(document).on("ready",inicio);
