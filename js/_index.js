$(document).ready(function() {

	$('select').material_select()

	iniciarSistema();

	$('#boton-atras').click(function() {
		console.log("click");
		window.location.href="index.html";
	});

//-----------------------------------boton-seguir-tdc-------------------------------------------------------------------
	$('#boton-seguir-detalle').click(function() {
		$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();
	});
	$('#boton-atras-user').click(function() {$("#panel-search").show();$("#panel-detalle").hide();$("#panel-tdc").hide();});
	$('#boton-seguir-tdc').click(function() {
		if($('#monto').val()==""|| $('#detalle').val()=="" ){
			Materialize.toast("Error, Complete los campos", 2000);
		}
		else{
			$("#panel-tdc").show();$("#panel-search").hide();$("#panel-detalle").hide();
		}

	});
	$('#boton-atras-detalle').click(function() {

		$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();

	});
	$('#borrar-user').click(function() {

		$("#seccion-img-user").show();$("#listado-user").hide();

	});

//------------------------------------------boton de pago-------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	String.prototype.replaceAll = function (search, replacement) {
		var target = this;
		return target.split(search).join(replacement);
	};

	function amountFormat(amount){
		amount = amount.replaceAll(' ','').replace(',','.');
		return amount.substr(0,amount.length-2);
	}

	$('#boton-pagar').click(function() {
		if($('#monto').val()!="" && $('#detalle').val()!="" && $('#nombre').val()!="" && $('#cedula').val()!=""
			&& $('#tdc').val()!="" && $('#cvv').val()!="" && $('#expirationDateMonth option:selected').val()!=""
			&& $('#expirationDateYear option:selected').val()!=""){
			$.ajax({
				url:'https://pagalofacil.com/services/instapago.php',
				method:'POST',
				data:{
					payment:
					{
						amount:$('#monto').val(),//monto
						description:$('#detalle').val(),//detalle
						cardHolder:$('#nombre').val(),//nombre
						cardHolderID:$('#cedula').val(),//cedula
						cardNumber:$('#tdc').val(),//numero TDC
						cvc:$('#cvv').val(), //codigo cvv
						expirationDateMonth:$('#expirationDateMonth option:selected').val(), //mes expiracion
						expirationDateYear:$('#expirationDateYear option:selected').val()    //año expiracion
					}
				}
			}).done(function (data) {
				//console.log(data);
				data = JSON.parse(data);
				//alert(data['message']);
				if(data['success']===true){
					//$('.form-group').hide();
					$('#voucher').html(data['voucher']);
					$('#voucher').html($('#voucher').text());

					/*$('#finish').click(function(e){
					 e.preventDefault();
					 $('button[type="submit"].button.btn.btn-default.button-medium').click();
					 });*/
					//$('#modal').modal();
					$('#modal1').openModal();
					//$('button[type="submit"].button.btn.btn-default.button-medium').click();
				}

			});
		}
		else{
			Materialize.toast("Error, Complete los campos", 2000);
		}

	});
	//---------------------------------------------FIN BOTON DE PAGO-----------------------------
	$("#sample_search").keyup(function()
	{
		$("#seccion-img-user").hide();
		console.log("buscar "+$(this).val());
		$("#listado-user").show();
		/* $.ajax({
		 type: "GET",
		 url: "servicio-usuario.php",
		 data: {'search_keyword' : value},
		 dataType: "text",
		 success: function(datos)
		 {
		 //Receiving the result of search here
		 }
		 });*/

	});

	$('.link-logout').on('click', function(e) {
		//alert("cerranbdo sesion");
		console.log("cerrar sesion ");
		sessionStorage.clear();
		$.ajax({
			url: "https://pagalofacil.com/services/ServicioUsuario.php",
			type: "POST",
			data: {accion: "cerrarSesion"},
			dataType: 'json',
			success: function(data){
				//console.log(data);
				//e.preventDefault();
				if(data.success)
				{
					if(data.flag){
						window.location="https://pagalofacil.com/index.html";
						sessionStorage.clear();
						Materialize.toast("Sesion cerrada", 4000);
					}

				}
				else
				{
					Materialize.toast("No se pudo cerrar sesion, error", 4000);

					// e.preventDefault();
				}
			}
		});
	});

});


function iniciarSistema(){
	//sessionStorage.setItem("d_s", true);
	iniciarventana();
	var valid=sessionStorage.getItem("d_s");

	if(valid){
		console.log("sesion iniciada");
		cargaTarjetas();
		//link-login
		$("#banner-oper").show();
		$("#banner-registro").hide();
		$(".link-registro").hide();
		$(".link-login").hide();
		$(".link-logout").show();
		$('#selectTDC').show();
	}
	else{
		console.log("NO login");
		$("#banner-oper").hide();
		$("#banner-registro").show();
		$(".link-registro").show();
		$(".link-login").show();
		$(".link-logout").hide();
		$('#selectTDC').hide();
	}

}

function iniciarventana(){
	$("#panel-search").show();
	$("#panel-detalle").hide();
	$("#panel-tdc").hide();
	$("#seccion-img-user").show();
	$("#listado-user").hide();
}

function cargaTarjetas(){
	//alert(sessionStorage.getItem("user"));
	$.ajax({
		url: "https://pagalofacil.com/services/ServicioTdc.php",
		type: "POST",
		data: {accion: "cargarTarjetas",usuario:sessionStorage.getItem("user") },
		dataType: 'json',
		success: function(data){
			//console.log(data);
			var $tarjetas = $("#comboTDC").empty().html(' ');

			if(data.success)
			{
				if(data.result_tarjetas){
					$tarjetas.append($("<option></option>").attr("value","").text("Seleccione una opción").attr("disabled", true).attr("selected", true));

					$.each( data.result_tarjetas, function( key, value ) {
						$tarjetas.append($("<option></option>").attr("value",value.id_tarjeta_asociada).text(value.numero_tarjeta));
					});

					$tarjetas.trigger('contentChanged');
				}
				else{
					$tarjetas.append($("<option></option>").attr("value","").text("No tiene tarjetas registradas").attr("disabled", true).attr("selected", true));
					$tarjetas.trigger('contentChanged');
				}

			}
			else{
				alert(data.message);
			}
		}
	});
}
