var inicio = function ()
{
	var claveUsuario = -1;
	var verificarUsuario = function()
	{
		var usu = $("#txtUsuario").val();
		var cve = $("#txtClave").val();
		if(usu!="" && cve!="")
		{
			var parametros = "opc=validaUsuario"+"&usuario="+usu+"&cveUsuario="+cve+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/usuarios.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						switch (response.tipo){
							case "1":
							$("#acceso").hide();
							$("#genericos").show("slow");
							 var parametros = "opc=usuario1"+"&clave1="+response.claveUsuario+"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/genericos.php",
				                    data: parametros, 
				                    success: function(data) {  
				                            $(".acceso").hide("slow");
				                            $(".accesoAlumno").show("slow");  
				                    }  
				                }); 
							break;
							case "2":
							$("#acceso").hide();
							$("#genericos").show("slow");
							break;
							case "3":
							$("#acceso").hide();
							$("#genericos").show("slow");
							break;
							case "4":
							$("#acceso").hide();
							$("#alumno").show("slow");
							break;
							case "5":
							$("#acceso").hide();
							$("#genericos").show("slow");
							break;
							case "6":
							$("#acceso").hide();
							$("#maestro").show("slow");
							//Pasando la claveUsuario
							 var parametros = "opc=usuario1"+"&clave1="+response.claveUsuario+"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/maestros.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {   
				                    }  
				                }); 
							//FIN MODIFICACIONES
							break;
						}
					}
					else
					{
						sweetAlert("Usuario y/o contraseña incorrectos", "tecle un usuario y/o contraseña correctos", "error");
					}
				},
				error: function(xhr,ajaxOptions,x){
					sweetAlert("Error de conexión1", "Problemas de conexión!", "error");
					usu = $("#txtUsuario").val("");
					cve = $("#txtClave").val("");
				}
			});
		}
		else
		{
			sweetAlert("Usuario y/o contraseña incorrectos", "tecle un usuario y/o contraseña correctos", "error");
		}
	}
	var usuarioNombre = function()
	{
		var parametros = "opc=claveUsuario1"+
		"&usuarionom="+$claveUsuario+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/usuarios.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					return response.claveUsuario;
					console.log("clave"+response.claveUsuario);
				}
			},
			error: function(xhr, ajaxOptions,x){
				alert("Error de conexión1");	
			}
		});
	}
	//Configuramos los eventos
	$("#btnIngresar").on("click",verificarUsuario);
}
$(document).on("ready",inicio);
