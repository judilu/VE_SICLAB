var inicio = function()
{
	$('select').material_select();
	var practicaAlumnos = function()
	{
		$("#accesoAlumno").hide();
		$("#datosPracticas").show("slow");
		var numeroControl = $("#txtNControl").val();
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
		$("input").val("");
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
		
	}
	/*function cargarSelect2(valor)
	{
	    var arrayValores=new Array(
	        new Array(1,1,"opcion1-1"),
	        new Array(1,2,"opcion1-2"),
	        new Array(1,3,"opcion1-3"),
	        new Array(2,1,"opcion2-1"),
	        new Array(3,1,"opcion3-1"),
	        new Array(3,2,"opcion3-2"),
	        new Array(3,3,"opcion3-3"),
	        new Array(3,4,"opcion3-4")
	    );
	    if(valor==0)
	    {
	        // desactivamos el segundo select
	        document.getElementById("select2").disabled=true;
	    }
	    else
	    {
	        // eliminamos todos los posibles valores que contenga el select2
	        document.getElementById("select2").options.length=0;
	 
	        // añadimos los nuevos valores al select2
	        document.getElementById("select2").options[0]=new Option("Selecciona una opcion", "0");
	        for(i=0;i<arrayValores.length;i++)
	        {
	            // unicamente añadimos las opciones que pertenecen al id seleccionado
	            // del primer select
	            if(arrayValores[i][0]==valor)
	            {
	                document.getElementById("select2").options[document.getElementById("select2").options.length]=new Option(arrayValores[i][2], arrayValores[i][1]);
	            }
	        }
	 
	        // habilitamos el segundo select
	        document.getElementById("select2").disabled=false;
	    }
	}*/

		/*function seleccinado_select2(value)
		{
		    var v1 = document.getElementById("select1");
		    var valor1 = v1.options[v1.selectedIndex].value;
		    var text1 = v1.options[v1.selectedIndex].text;
		    var v2 = document.getElementById("select2");
		    var valor2 = v2.options[v2.selectedIndex].value;
		    var text2 = v2.options[v2.selectedIndex].text;
		 
		    alert("Se ha seleccionado el valor "+valor1+" ("+text1+") del primer select y el valor "+valor2+" ("+text2+") del segundo select");
		}*/

	$("#btnPractica").on("click",practicaAlumnos);
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
	$("#btnCancelarEntrada").on("click",consultaAlumno);
}
$(document).on("ready",inicio);
