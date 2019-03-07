function isNumber(event){
  var charCode = (event.which) ? event.which : event.keyCode;
  
  console.log(charCode);
  
  if(charCode==8||charCode==9||charCode==37||charCode==39||charCode==46){
  	return true;
  }
  
  if (charCode > 31 && (charCode < 48 || charCode > 57))
  	return false;
  	
  return true;
} 

$( "#ultimos" ).on( "click", ".ultimos-cinco", function() {
	$('#sample_search').val($(this).attr('id'));
	$("#sample_search").focusin();
});

$( "#favoritos" ).on( "click", ".cliente-favorito", function() {
	$('#sample_search').val($(this).attr('id'));
	$("#sample_search").focusin();
});

$( "#agregar_favorito" ).on( "click", ".material-icons", function() {
	$.ajax({
		url: "services/ServicioUsuario.php",
		type: "POST",
		data: {
			accion: "agregarFavorito", id_cliente:sessionStorage.getItem("id_cliente"), favorito: $("#sample_search").val()
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if(data.success) {
				cargarFavoritos();
				//Materialize.toast("Favorito agregado satisfactoriamente", 4000);
			}
			/*else {
				Materialize.toast("Error. No se pudo agregar a favoritos", 4000);
			}*/
			Materialize.toast(data.message, 4000);

		}

	});
});

$(document).ready(function() {

	//------------------------------validaciones-formulario-de-pago-------------------------------------------------


	/*$('#sample_search').keypress(function(e){
		var n = e.keyCode;

		if(!isNumber(e)||$(this).val().length>8){
			return false;
		}
	});*/
	$('#cvv').keypress(function(e){
		var n = e.keyCode;

		if(!isNumber(e)||$(this).val().length>2){
			return false;
		}
	});

	$('#cedula,#tdc,#monto').keypress(function(e){
		var n = e.keyCode;

		if(!isNumber(e)){
			return false;
		}
	});

	$('#nombre').unbind('keypress').keypress(function(e){
		var charCode = e.keyCode || e.which;
		
		  if(charCode==8||charCode==9||charCode==37||charCode==39||charCode==46){
		  	return true;
		  }
		
		var c = String.fromCharCode(charCode);

		if(!/^[\sñÑA-Za-z _]*[\sñÑA-Za-z][\sñÑA-Za-z _]*$/.test(c)){
			return false;
		}
	});
//------------------------------fin-validaciones-formulario-de-pago-------------------------------------------------

	//-------------------------------cargar-ultimos-5-----------------------------------------------------
	$.ajax({
		url: "services/ServicioUsuario.php",
		type: "POST",
		data: {
			accion: "cargarUltimosCinco", id_cliente:sessionStorage.getItem("id_cliente")
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if (data.success) {

				$('#ultimos').html(data.clientes);
			}
			else {

				$('#ultimos').html('&emsp;<i>No se encontró ningún registro</i>');
			}

		}

	});

	//-------------------------------fin-cargar-ultimos-5-------------------------------------------------

	//-------------------------------cargar-favoritos-----------------------------------------------------
	cargarFavoritos();
	/*$.ajax({
		url: "services/ServicioUsuario.php",
		type: "POST",
		data: {
			accion: "cargarFavoritos", id_cliente:sessionStorage.getItem("id_cliente")
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if (data.success) {

				$('#favoritos').html(data.clientes);
			}
			else {

				$('#favoritos').html('&emsp;<i>No se encontró ningún registro</i>');
			}

		}

	});*/

	//-------------------------------fin-cargar-favoritos-------------------------------------------------

	/*var tipo = $( "input:checked" ).attr("id");

	if(tipo == 2){

		agregarParticipante(sessionStorage.getItem("id_cliente"));
	}*/

});

function cargarFavoritos() {
	$.ajax({
		url: "services/ServicioUsuario.php",
		type: "POST",
		data: {
			accion: "cargarFavoritos", id_cliente:sessionStorage.getItem("id_cliente")
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if (data.success) {

				$('#favoritos').html(data.clientes);
			}
			else {

				$('#favoritos').html('&emsp;<i>No se encontró ningún registro</i>');
			}

		}

	});
}

function agregarParticipante(idParticipante) {

	var participantes = $("#participantes_pago_colectivo").val();

	if(participantes != "")
	{
		$("#participantes_pago_colectivo").val(participantes+','+idParticipante);
	}
	else
	{
		$("#participantes_pago_colectivo").val(idParticipante);
	}

	$.ajax({
		url: "services/ServicioUsuario.php",
		type: "POST",
		data: {
			accion: "agregarParticipantePagoColectivo", id_participante: idParticipante
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if (data.success) {
				contenido = $('#participantes').html();
				$('#participantes').html(contenido + '<br>' + data.participante);
			}
			else {

				Materialize.toast("No se pudo agregar el participante, error", 4000);
			}

		}

	});
}

$( "#participantes" ).on( "click", ".eliminar-participante", function() {

	var participantes = $("#participantes_pago_colectivo").val();

	var id = $(this).attr('id');
	id = id.substring(9);

	$("#"+id).remove();

	id = id.substring(3);

	participantes = participantes.replace(id+',', '');
	participantes = participantes.replace(id, '');

	$("#participantes_pago_colectivo").val(participantes);
	
});

function generarFactura(tipoFactura) {

	var receptor = $("#sample_search").val();
	var monto = $("#monto").val();
	var detalle = $("#detalle").val();
	var participantes = $("#participantes_pago_colectivo").val();
	//var participantes_temp = participantes.split(",");
	var montos_colectivo = "";

	$(".monto-participante").each(function()
	{  
		montos_colectivo = montos_colectivo + $(this).val() + ",";
	})

	var tam = montos_colectivo.length;
	montos_colectivo = montos_colectivo.substring(0, tam-1);


	$.ajax({
		url: "services/ServicioPago.php",
		type: "POST",
		data: {
			accion: "generarFactura", id_cliente:sessionStorage.getItem("id_cliente"), receptor: receptor, monto: monto, detalle: detalle, 
			participantes: participantes, tipo_factura: tipoFactura, montos_colectivo: montos_colectivo
		},
		dataType: 'json',
		success: function(data){

			//console.log(data);
			if (data.success) {

				window.location="https://pagalofacil.com/orden-pago.html";
			}
			Materialize.toast(data.message, 4000);

		}

	});
}