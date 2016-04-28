var inicioreporte = function ()
{
	var llenarDatosReporte = function ()
	{
		var variables = obtenerDatos();
		console.log(variables);
		//llenar datos
		//$("#txtClaveMaestroRp").val(variables['cveMaestro']);
	}
	var obtenerDatos = function()
	{
		var dir = document.location.href;
		var dr 	= dir.split('?')[1];
		var dat = dr.split('&');
		var variables = {};//objeto
		var con = dat.length; 
		for (var i =0; i < con; i++) 
		{
			var aux = dat[i].split('=');
			variables[aux[0]] = unescape(decodeURI(aux[1]));
		}
		return variables;
	}
	llenarDatosReporte();	
}
$(document).on("ready",inicioreporte);