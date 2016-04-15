var inicio = function()
{
	$('select').material_select();

	var practicaAlumnos = function()
	{
		if(($("#txtNControl").val())!="" && ($("#txtNombre").val())!="") 
		{
			$("#accesoAlumno").hide();
			$("#datosPracticas").show("slow");
			var numeroControl = $("#txtNControl").val();
			$("#txtNControlAlu").val(numeroControl);
			$("#txtNombreAlu").val($("#txtNombre").val());
			var parametros = "opc=consultaMatAlumno"+
			"&numeroControl="+numeroControl+
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
		$("#datosPractica2").hide();
		$("#materialAlumno").show("slow");
		$("#txtNumeroControlPrestamo").val($("#txtNControlAlu").val());
		$("#txtNombreAluPrestamo").val($("#txtNombreAlu").val());

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
		var claveMaestro = $("#cmbMaestrosMat").val();
		var parametros = "opc=consultaPracticaNombre"+
		"&claveMaestro="+claveMaestro+
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
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombrePracticas").append($("<option></option>").attr("value",response.clavePractica[i]).text(response.nombrePractica[i]));
						}
						$("#cmbNombrePracticas").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					sweetAlert("No hay practicas asignadas a ese maestro", "", "error");
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
		var clavePrac 	 	= $("#cmbNombrePracticas").val();
		var parametros 		= "opc=consultaHoraPractica"+
								"&clavePrac="+clavePrac+
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
   					$("#cmbHorariosPracticas").html(" ");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbHorariosPractica").append($("<option></option>").attr("value",response.clavePractica[i]).text(response.horaPractica[i]));
						}
						$("#cmbHorariosPractica").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					sweetAlert("No existe la practica", "", "error");
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
		$("#acceso").hide();
		$("#alumno").show("slow");
		$("#accesoAlumno").show("slow");
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
	$("#cmbMateriasAlumnos").on("change",maestroPractica);
	$("#cmbMaestrosMat").on("change",nombrePracticaMaestro);
	$("#cmbNombrePracticas").on("change",horarioPractica);
	$("#btnEntradaAlumno").on("click",guardaEntradaAlumno);
	$("#btnCancelarEntrada").on("click",cancelaEntrada);
}
$(document).on("ready",inicio);
