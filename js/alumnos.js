var inicio = function()
{
	$('select').material_select();
	var practicaAlumnos = function()
	{
		$("#accesoAlumno").hide();
		$("#datosPracticas").show("slow");
		var numeroControl = $("#txtNControl").val();
		alert(numeroControl);
		if (($("#txtNControl").val())!="") 
		{
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
					for (var i = 0; i < cmbMaterias.length; i++) 
					{
   						$('#cmbMaterias').append(response.cmbMaterias[i]);
					}
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
	}
	var materialPractica = function()
	{
		$("#datosPractica2").hide();
		$("#materialAlumno").show("slow");
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
					document.getElementById('txtNombre').focus()
					$("#txtNombre").val(response.ALUAPP+" "+ response.ALUAPM+ response.ALUNOM);	
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
	var maestroPractica = function()
	{
		var claveMateria = $("#cmbMateriaAlumno").val();
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
					alert("entra aqui ");
   							//$("#cmbMaestroPractica").append(response.opcionMaestro);
   							$('#cmbMaestroPractica').append('<option value="0">SELECCIONAR...</option>');
   							for (i=0; i < response.opcionMaestro.length; i++)
   							{
   								$(".cMaestro ul").append('<li class>'+response.nombreMaestro[i]+'</li>');
   								$('#cmbMaestroPractica').append('<option value='+response.opcionMaestro[i]+'>'+response.nombreMaestro[i]+'</option>');
   							}
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
	var numMaestro = function()
	{
		alert($("#cmbMaestroPractica").val());
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
	$("#cmbMateriaAlumno").on("change",maestroPractica);
	$("#cmbMaestroPractica").on("change",numMaestro);

	//$("#btnCancelarEntrada").on("click",consultaAlumno);
}
$(document).on("ready",inicio);
