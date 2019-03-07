$(document).ready(function() {

	$('select').material_select();

	iniciarSistema();

	$('#boton-atras').click(function() {
		console.log("click");
		window.location.href="index.html";
	});

	
//-----------------------------------boton-seguir-tdc-------------------------------------------------------------------
	$('#boton-seguir-detalle').click(function() {

		if($("#sample_search").val()=="" ){
			Materialize.toast("Aviso: Complete los campos", 2000);
		}
		else{
			$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();
		}

	});
	$('#boton-atras-user').click(function() {$("#panel-search").show();$("#panel-detalle").hide();$("#panel-tdc").hide();});
	$('#boton-seguir-tdc').click(function() {
		var tipo = $( "input:checked" ).attr("id");

		if($('#monto').val()==""|| $('#detalle').val()=="" ){
			Materialize.toast("Aviso: Complete los campos", 2000);		}
		else if(tipo == 1){ 
			$("#panel-tdc").show();$("#panel-search").hide();$("#panel-detalle").hide();
		}
		else if(tipo == 2){ 
			$("#panel-colectivo").show();$("#panel-search").hide();$("#panel-detalle").hide();

			agregarParticipante(sessionStorage.getItem("id_cliente"));
		}

	});
	$('#boton-atras-detalle').click(function() {

		$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();

	});
	$('#borrar-user').click(function() {

		$("#seccion-img-user").show();$("#listado-user").hide();

	});

	$('#boton-atras-detalle').click(function() {
		$("#panel-detalle").show();$("#panel-search").hide();$("#panel-tdc").hide();$("#panel-colectivo").hide();
		$("#participantes").html("");
		$("#participantes_pago_colectivo").val("");
	});
	$('#boton-seguir-tdc2').click(function() {

		var monto_total = 0;

		$(".monto-participante").each(function()
		{  
			monto_total = monto_total + parseFloat($(this).val());
		})

		if(monto_total != $('#monto').val()){
			Materialize.toast("Aviso: La suma del monto a cancelar por cada integrante debe ser igual al monto de la factura", 6000);		
		} 
		else {
			//$("#panel-tdc").show();$("#panel-search").hide();$("#panel-detalle").hide();$("#panel-colectivo").hide();
			generarFactura(2);
			
		}
		/*if($('#monto').val()==""|| $('#detalle').val()=="" ){
			Materialize.toast("Aviso: Complete los campos", 2000);		}
		else if(tipo == 1){ 
			$("#panel-tdc").show();$("#panel-search").hide();$("#panel-detalle").hide();
		}
		else if(tipo == 2){ 
			$("#panel-colectivo").show();$("#panel-search").hide();$("#panel-detalle").hide();
		}*/

	});
	$('#boton-atras-detalle2').click(function() {
		var tipo = $( "input:checked" ).attr("id");

		if(tipo == 1){ 
			$("#panel-detalle").show();$("#panel-search").hide();$("#panel-tdc").hide();$("#panel-colectivo").hide()
		}
		else if(tipo == 2){ 
			$("#panel-colectivo").show();$("#panel-search").hide();$("#panel-detalle").hide();$("#panel-tdc").hide();

			//agregarParticipante(sessionStorage.getItem("id_cliente"));
		}
		//$("#panel-detalle").show();$("#panel-search").hide();$("#panel-tdc").hide();$("#panel-colectivo").hide();
	});

//-----------------------------------boton-borrar-tdc-------------------------------------------------------------------

	$('#boton-borrar-colectivo').click(function() {
			
			$("#sample_search_colectivo").val("");
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

			$('.button-footer').find('.row.preloader').removeClass('hide');
			$('.action_buttons').addClass('hide');

			$.ajax({
				url:'https://pagalofacil.com/services/instapago.php',
				method:'POST',
				data:{
					user:$("#sample_search").val(),
					payment:
					{

						amount:$('#monto').val(),//monto
						description:$('#detalle').val(),//detalle
						cardHolder:$('#nombre').val(),//nombre
						cardHolderID:$('#cedula').val(),//cedula
						cardNumber:$('#tdc').val(),//numero TDC
						cvc:$('#cvv').val(), //codigo cvv
						expirationDateMonth:$('#expirationDateMonth option:selected').val(), //mes expiracion
						expirationDateYear:$('#expirationDateYear option:selected').val()    //a�0�9o expiracion
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
				else
				{
					Materialize.toast(data['message'], 2000);
					$('.button-footer').find('.row.preloader').addClass('hide');
					$('.action_buttons').removeClass('hide');
				}

			}).fail(function () {
				$('.button-footer').find('.row.preloader').addClass('hide');
				$('.action_buttons').removeClass('hide');
			});
		}
		else{
			Materialize.toast("Error, Complete los campos", 2000);
		}

	});
	//---------------------------------------------FIN BOTON DE PAGO-----------------------------
	$("#sample_search").keyup(function()
	{
        var listado = $("#listado-user");
		$("#seccion-img-user").hide();

        if($(this).val().length<2){
            return;
        }

       /* if(isNaN($(this).val())){
            listado.html('<a href="#!" class="collection-item">No hay resultados</a>');
            listado.show();

            listado.find('.collection-item').click(function () {
                $(this).parent().hide();
            });
            return;
        }*/

		$.post("services/ServicioUsuario.php",{'accion':'buscarUsuario','telefono':$(this).val()})
			.done(function (data) {
				console.log(data);
				data = JSON.parse(data);
                listado.html('');
                $.each(data,function (key,val) {
                    listado.append('<a href="#!" class="collection-item">'+val.cedula+' - '+val.correo+'</a>');
                });

                listado.find('.collection-item').click(function () {
                    if($(this).text()!=='No hay resultados' && $(this).text()!=='undefined') {
                        $("#sample_search").val($(this).text());
                        $("#btn_favorito").css("display", "");
                    }
                    else {
                    	$("#btn_favorito").css("display", "none");	
                    }

                    $(this).parent().hide();
                });
                listado.show();
			});
	}).focus(function () {
		$('#icono_buscar').hide();
	}).blur(function () {
        if($(this).val()===''){
            $('#icono_buscar').show();
            $("#listado-user").hide();
        }
	});


	$("#sample_search_colectivo").keyup(function()
	{
        var listado = $("#listado-user-colectivo");
        var id_participante_agregar = -1;
		//$("#seccion-img-user").hide();

        if($(this).val().length<2){
            return;
        }

        if(isNaN(parseInt($(this).val()))){
            listado.html('<a href="#!" class="collection-item">No hay resultados</a>');
            listado.show();

            listado.find('.collection-item').click(function () {
                $(this).parent().hide();
            });
            return;
        }

		$.post("services/ServicioUsuario.php",{'accion':'buscarUsuarioColectivo','telefono':$(this).val(), 'participantes':$("#participantes_pago_colectivo").val()})
			.done(function (data) {
				data = JSON.parse(data);
                listado.html('');
                $.each(data,function (key,val) {
                    listado.append('<a href="#!" id="'+val.id_cliente+'" class="collection-item">'+val.cedula+'</a>');
                });

                listado.find('.collection-item').click(function () {
                    if($(this).text()!=='No hay resultados' && $(this).text()!=='undefined')
                    	agregarParticipante($(this).attr('id'));
                        //$("#sample_search_colectivo").val($(this).text());

                    $("#sample_search_colectivo").val('');
                    $(this).parent().hide();
                });
                listado.show();
			});
	/*}).focus(function () {
		$('#icono_buscar').hide();*/
	}).blur(function () {
        if($(this).val()===''){
           // $('#icono_buscar').show();
            $("#listado-user-colectivo").hide();
        }
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
		$(".link-perfil").show();
		$(".link-logout").show();
		$('#selectTDC').show();
		$('#acordeon').css('display', '');
		$('#seccion-img-user').css('display', 'none');
		$('#tipo').css('display', '');
	}
	else{
		console.log("NO login");
		$("#banner-oper").hide();
		$("#banner-registro").show();
		$(".link-registro").show();
		$(".link-login").show();
		$(".link-perfil").hide();
		$(".link-logout").hide();
		$('#selectTDC').hide();
		$('#acordeon').css('display', 'none');
		$('#seccion-img-user').css('display', '');
		$('#tipo').css('display', 'none');
	}

}

function iniciarventana(){
	$("#panel-search").show();
	$("#panel-detalle").hide();
	$("#panel-colectivo").hide();
	$("#panel-tdc").hide();
	$("#seccion-img-user").show();
	$("#listado-user").hide();
	$("#listado-user-colectivo").hide();
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
					$tarjetas.append($("<option></option>").attr("value","").text("Seleccione una opci��n").attr("disabled", true).attr("selected", true));

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
