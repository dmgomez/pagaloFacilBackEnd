$(document).ready(function() {

  var valid=sessionStorage.getItem("d_s");

  //alert(valid);
  var id_factura = getParameterByName('i');

  if(valid == "true"){
    console.log("sesion iniciada");
    cargarDatosFactura(id_factura);
    }
  else{
    console.log("NO login"); 
    window.location="https://pagalofacil.com/login.html?i="+id_factura;   
  }

  $('select').material_select();

  $.ajax({   
      url: "https://pagalofacil.com/services/ServicioUsuario.php",
      type: "POST",
      data: {accion: "cargarTarjeta", id_cliente:sessionStorage.getItem("id_cliente") },
      dataType: 'json',
      success: function(data){  
        //console.log(data);  
        var $tdc = $("#comboTDC").empty().html(' ');

        if(data.success)
        {
          $tdc.append($("<option></option>").attr("value","").text("Seleccione una opción").attr("disabled", true).attr("selected", true));
           
          $.each( data.result_tdc, function( key, value ) {
            $tdc.append($("<option></option>").attr("value",value.id_tarjeta_asociada).text(value.nombre_empresa+' - '+value.numero_tarjeta));
          });

          $tdc.trigger('contentChanged');
        }
        else{
          Materialize.toast(data.message, 4000); 
        }
      }
  });

  $('select').on('contentChanged', function() {
    // re-initialize (update)
    $(this).material_select();
  }); 


  $('.modal').modal();

});


function cargarDatosFactura(idFactura){
  $.ajax({

    url: "services/ServicioPago.php",
    type: "POST",
    data: {accion: "buscarDatosFactura", id_cliente:sessionStorage.getItem("id_cliente"), id_factura: idFactura},
    dataType: 'json',
    success: function(data){  

      if(data.success)
      { 
        $('#total').val(data.datos_pago.total_factura).focusin();
        $('#detalle').val(data.datos_pago.detalle_factura).focusin();
        $('#destinatario').val(data.datos_pago.destinatario).focusin();
        $('#monto').val(data.datos_pago.monto_transaccion).focusin();

        if(data.datos_pago.titular != null)
        {
          $('#titular').val(data.datos_pago.titular).focusin();
          $('#cedula').val(data.datos_pago.ci_titular).focusin();
          $('#tdc').val(data.datos_pago.numero_tarjeta).focusin();
        }

        if(data.datos_pago.id_estatus_transaccion == 1)
        {
          $("#btn-aceptar").removeClass("disabled");
          $("#btn-denegar").removeClass("disabled");
        }
        else
        {
          $(".select-dropdown").attr("disabled", "disabled");
          $("#cvv").attr("disabled", "disabled");
        }

        if(data.datos_cliente.id_emisor_pago == data.datos_cliente.id_emisor_transaccion)
        {
          $.ajax({

            url: "services/ServicioPago.php",
            type: "POST",
            data: {accion: "buscarDatosParticipante", id_factura: idFactura},
            dataType: 'json',
            success: function(data){  

              if(data.success)
              { 
                $("#test2").html(data.tab_participante);

                setTimeout('temporizador()', 60000);
              }
              else
              {

                Materialize.toast(data.message, 4000); 

              }

            }

          });
        }
        else
        {
          $("#participantes").css("display", "none");
        }

       /* $.each(data.bancos,function (key,val) {
          $('#banco_id_banco').append('<option value="'+val.id_banco+'">'+val.nombre+'</option>');
        });

        $.each(data,function (key,val) {
          $('#'+key).val(val).focusin();
        });
*/

        //$("#password").val(data.contrasena);
        
      }
      else
      {

        Materialize.toast(data.message, 4000);  

        //sessionStorage.setItem("d_s", false);

      }

    }

  });
}



function temporizador() {

  var id_factura = getParameterByName('i');

  $.ajax({
    url: "services/ServicioPago.php",
    type: "POST",
    data: {
      accion: "actualizarEstadoParticipante", id_factura: id_factura
    },
    dataType: 'json',
    success: function(data){  
       
       //console.log(data);
       
      if(data.success)
      {  
        var edo_aceptada = 5; 
        var edo_aprobada = 2;
        var actualizar = false;
        $.each(data.result_estado,function (key,val) {
          $('#estado_p_'+val.emisor_id).html(val.descripcion_estatus_transaccion);

          if(val.id_estatus_transaccion != edo_aceptada && val.id_estatus_transaccion != edo_aprobada )
          {
            actualizar = true;
          }
          /*else
          {
            $("#reasignar_"+val.emisor_id).addClass("disabled");
            $("#reenviar_"+val.emisor_id).addClass("disabled");
          }*/
          if(val.id_estatus_transaccion == 1 && val.emisor_id)
          {
            $(".select-dropdown").attr("disabled", false);
            $("#cvv").attr("disabled", false);
          }
          else
          {
            $(".select-dropdown").attr("disabled", "");
            $("#cvv").attr("disabled", "");
          }
          
          if(val.id_estatus_transaccion == 1 || val.id_estatus_transaccion == 2 || val.id_estatus_transaccion == 5)
          {
            $("#reasignar_"+val.emisor_id).addClass("disabled");
            $("#reenviar_"+val.emisor_id).addClass("disabled");

            //$(".select-dropdown").attr("disabled", "");
            //$("#cvv").attr("disabled", "");
          }
          else
          {
          	$("#reasignar_"+val.emisor_id).removeClass("disabled");
            $("#reenviar_"+val.emisor_id).removeClass("disabled");
          }
        });

        if(actualizar)
        {
          setTimeout("temporizador()", 60000);
        }
        else
        {
          $.ajax({
            url:'https://pagalofacil.com/services/instapagoColectivo.php',
            method:'POST',
            data:{
              payment: data.result_estado
            }
          }).done(function (data) {
            //console.log(data);
            data = JSON.parse(data);
            //alert(data['message']);
            if(data['success']===true){
              //$('.form-group').hide();
              /*$('#voucher').html(data['voucher']);
              $('#voucher').html($('#voucher').text());

              $('#modal2').openModal();*/
              $.ajax({
                url:'https://pagalofacil.com/services/envioEmail.php',
                method:'POST',
                data:{
                  receptor: data.receptor
                }
              });
            }
            else
            {
              //Materialize.toast(data['message'], 2000);
              Materialize.toast('Algunas transacciones fallaron. Revise los datos suministrados', 2000);
              
            }

            setTimeout("temporizador()", 100);

          }).fail(function () {
            setTimeout("temporizador()", 100);
          });


        }
      }
      else
      {
        Materialize.toast(data.message, 4000); 

      }
      
    }

  });
  //$(document).ready(function()
  //{
    //configPopover();
    
        
    //location.reload();
      //timer = setTimeout("temporizador()", 2000);

  //});
}

$('#participantes').on('click', function(e) {  

  setTimeout("temporizador()", 100);
  
}); 


$('#btn-aceptar').on('click', function(e) {  

  var datos_tdc = {};
  datosIncompletos = false;
  datosInvalidos = false;

  $.each($('input,select').not('[disabled]'),function (key,val) {
    if($(val).attr('id')!=undefined&&$(val).attr('id')!=""){
      if($(val).val()==""){
          datosIncompletos = true;
          //Materialize.toast('Debe llenar los campos solicitados', 4000);
          return;
      }
      datos_tdc[$(val).attr('id')] = $(val).val();
    }
  });

  //}

  $(".invalid").each(function()
  { 
    datosInvalidos = true;
  });

  if(datosIncompletos)
  {
    e.preventDefault();
    Materialize.toast('Debe llenar los datos de la tarjeta', 4000);
    return;
  }
  else if(datosInvalidos)
  {
    e.preventDefault();
    Materialize.toast('Debe corregir los errores del formulario', 4000);
    return;
  }
  else
  {
    var id_factura = getParameterByName('i');

    $.ajax({
      url: "services/ServicioPago.php",
      type: "POST",
      data: {
        accion: "actualizarEstadoAceptar", id_cliente:sessionStorage.getItem("id_cliente"), id_factura: id_factura, datos_tdc: datos_tdc
      },
      dataType: 'json',
      success: function(data){  
         
         console.log(data);
         Materialize.toast(data.message, 4000); 

        if(data.success)
        {   
          $("#btn-aceptar").addClass("disabled");
          $("#btn-denegar").addClass("disabled");

          $(".select-dropdown").attr("disabled", "disabled");
          $("#cvv").attr("disabled", "disabled");
        }
        
      }

    });
  }
  
}); 



$('#btn-denegar').on('click', function(e) {  

    var id_factura = getParameterByName('i');

    $.ajax({
      url: "services/ServicioPago.php",
      type: "POST",
      data: {
        accion: "actualizarEstadoDenegar", id_cliente:sessionStorage.getItem("id_cliente"), id_factura: id_factura
      },
      dataType: 'json',
      success: function(data){  
         
         console.log(data);
         Materialize.toast(data.message, 4000); 

        if(data.success)
        {   
          $("#btn-aceptar").addClass("disabled");
          $("#btn-denegar").addClass("disabled");

          $(".select-dropdown").attr("disabled", "disabled");
          $("#cvv").attr("disabled", "disabled");
        }
        
      }

    });
  
  
}); 



//$('.reenviar-solicitud').on('click', function(e) {
function reenviarSolicitud(id){

  	/*var id = $(this).attr("id");
  	id = id.split("_");
*/
  
    var id_factura = getParameterByName('i');

    $.ajax({
      	url: "services/ServicioPago.php",
      	type: "POST",
      	data: {
        	accion: "reenviarSolicitud", id_cliente: id/*[1]*/, id_factura: id_factura
      	},
      	dataType: 'json',
      	success: function(data){  
         
         	console.log(data);
         	Materialize.toast(data.message, 4000); 

	        if(data.success)
	        {   
	          	/*$("#reasignar_"+val.emisor_id).addClass("disabled");
	          	$("#reenviar_"+val.emisor_id).addClass("disabled");*/
	          	setTimeout("temporizador()", 100);
	        }
        
      	}

    });
  
      
} 
//}); 


function reasignarSolicitud(id){
  
    var id_factura = getParameterByName('i');

    $.ajax({
        url: "services/ServicioPago.php",
        type: "POST",
        data: {
          accion: "reasignarParticipante", id_cliente: id/*[1]*/, id_factura: id_factura
        },
        dataType: 'json',
        success: function(data){  
         
          console.log(data);
          

          if(data.success)
          {   
              /*$("#reasignar_"+val.emisor_id).addClass("disabled");
              $("#reenviar_"+val.emisor_id).addClass("disabled");*/
              $("#modal-participantes").html(data.tab_participante);
          }
          else
          {
          	  Materialize.toast(data.message, 4000); 
          }
        
        }

    });
  

    /*$.ajax({
        url: "services/ServicioPago.php",
        type: "POST",
        data: {
          accion: "reasignarSolicitud", id_cliente: id, id_factura: id_factura
        },
        dataType: 'json',
        success: function(data){  
         
          console.log(data);
          Materialize.toast(data.message, 4000); 

          if(data.success)
          {   
              setTimeout("temporizador()", 100);
          }
        
        }

    });
  */
      
}


function reasignarSolicitudParticipante(id, id2){
  
    var id_factura = getParameterByName('i');
    $('#modal1').modal('close');

    $.ajax({
        url: "services/ServicioPago.php",
        type: "POST",
        data: {
          accion: "reasignarSolicitud", id_cliente_reasignar: id, id_cliente_reasignado: id2, id_factura: id_factura
        },
        dataType: 'json',
        success: function(data){  
         
          console.log(data);
          Materialize.toast(data.message, 4000); 

          if(data.success)
          {   
              setTimeout("temporizador()", 100);
          }
        
        }

    });
  
      
}


$('#btnSubmitUser').on('click', function(e) {  
/*
  var telefono = $("#telefono").val();
  var direccion = $("#direccion").val();
  var correo = $("#correo").val();
  var password = $("#password").val();
  var password_confirm = $("#password_confirm").val();
*/
  var datos_perfil = {};
  datosIncompletos = false;
  datosInvalidos = false;
  
  //if(telefono == "" || correo == "" || password == "" || password_confirm == "")
  //{
  $.each($('input,select').not('[disabled]'),function (key,val) {
    if($(val).attr('id')!=undefined&&$(val).attr('id')!=""){
      if($(val).val()==""){
          datosIncompletos = true;
          //Materialize.toast('Debe llenar los campos solicitados', 4000);
          return;
      }
      datos_perfil[$(val).attr('id')] = $(val).val();
    }
  });

  //}

  $(".invalid").each(function()
  { 
    datosInvalidos = true;
  });

  if(datosIncompletos)
  {
    e.preventDefault();
    Materialize.toast('Debe llenar todos los datos de usuario', 4000);
    return;
  }
  else if(datosInvalidos)
  {
    e.preventDefault();
    Materialize.toast('Debe corregir los errores del formulario', 4000);
    return;
  }
  else
  {
    if(datos_perfil.contrasena!=datos_perfil.contrasena_confirm){
      Materialize.toast('ERROR: Contrase&ntilde;as no coinciden', 4000);
      return;
    }

    $.ajax({
      url: "https://pagalofacil.com/services/ServicioUsuario.php",
      type: "POST",
      data: {
        accion: "actualizarPerfil", datos_perfil:datos_perfil/*telefono: telefono, direccion: direccion,
        correo: correo, contrasena: password, id_cliente:sessionStorage.getItem("id_cliente")*/
      },
      dataType: 'json',
      success: function(data){  
         
         console.log(data);
         Materialize.toast(data.message, 4000); 

        if(data.success)
        {   
          console.log("perfil actualizado");

          window.location="https://pagalofacil.com";
        }
        else
        {
          //Materialize.toast(data.message, 4000); 
          e.preventDefault();
        }

      }

    });
  }
}); 