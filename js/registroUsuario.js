$(document).ready(function() {

  $('select').material_select();


  $('select[name="tipo_id"]').change(function(){
    if($(this).val()=='J')
    {
      $('#apellido').val(' ').parent().addClass('hide');
      $('#nombre').removeClass('validate invalid').parent().removeClass('l6 m6').addClass('l12 m12').find('label').html('Nombre/Razón social');
      $('#cedula').parent().find('label').html('R.I.F.');
    }
    else
    {
      $('#apellido').val('').parent().removeClass('hide');
      $('#nombre').addClass('validate').parent().addClass('l6 m6').removeClass('l12 m12').find('label').html('Nombre');
      $('#cedula').parent().find('label.hide-on-med-and-down').html('Cédula de identidad');
      $('#cedula').parent().find('label.hide-on-large-only').html('C.I.');
    }

  });

	$.ajax({   
      url: "https://localhost/services/ServicioUsuario.php",
      type: "POST",
      data: {accion: "cargarComboEmpresaEmisora" },
      dataType: 'json',
      success: function(data){  
        //console.log(data);	
        var $empresaEmisora = $("#id_empresa_emisora").empty().html(' ');

      	if(data.success)
      	{
          $empresaEmisora.append($("<option></option>").attr("value","").text("Seleccione una opción").attr("disabled", true).attr("selected", true));
           
          $.each( data.result_empresa, function( key, value ) {
            $empresaEmisora.append($("<option></option>").attr("value",value.id_empresa_emisora).text(value.nombre_empresa));
          });

          $empresaEmisora.trigger('contentChanged');
  			}
  			else{
  				alert(data.message);
  			}
     	}
  });

  $.ajax({   
      url: "https://localhost/services/ServicioUsuario.php",
      type: "POST",
      data: {accion: "cargarComboBancoEmisor" },
      dataType: 'json',
      success: function(data){  
        
        var $bancoEmisor = $("#id_banco").empty().html(' ');

        if(data.success)
        {
          $bancoEmisor.append($("<option></option>").attr("value","").text("Seleccione una opción").attr("disabled", true).attr("selected", true));
           
          $.each( data.result_banco, function( key, value ) {
            $bancoEmisor.append($("<option></option>").attr("value",value.id_banco).text(value.nombre));
          });

          $bancoEmisor.trigger('contentChanged');
        }
        else{
          alert(data.message);
        }
      }
  });

  $('select').on('contentChanged', function() {
    // re-initialize (update)
    $(this).material_select();
  }); 
  
});

var password = document.getElementById("password"), confirm_password = document.getElementById("password_confirm");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;


/*$().ready(function() {
  $('#registro').validate({
    errorElement : "div",
    errorPlacement : function(error, element) {
      var placement = $(element).data('error');
      alert(placement);
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    }
  });
});*/



$('#btnSubmit').on('click', function(e) {

  //e.preventDefault();

  var nombre = $("#nombre").val();
  var apellido = $("#apellido").val();
  var cedula = $("#cedula").val();
  var codigo_celular = $("#codigo_celular").val();
  var telefono = $("#telefono").val();
  var direccion = $("#direccion").val();
  var correo = $("#correo").val();
  var username = $("#username").val();
  var password = $("#password").val();
  var password_confirm = $("#password_confirm").val();
  
  var titular_tarjeta = $("#nombre_titular").val();
  var ci_tarjeta = $("#ci_titular").val();
  var num_tarjeta = $("#numero_tarjeta").val();
  var mes_venc = $("#mes_vencimiento").val();
  var ano_venc = $("#ano_vencimiento").val();
  var empresa_emisora = $("#id_empresa_emisora").val();
  var direccion_tarjeta = $("#direccion_titular").val();

  var titular_cuenta = $("#nombre_titular_cuenta").val();
  var ci_cuenta = $("#ci_titular_cuenta").val();
  var num_cuenta = $("#numero_cuenta").val();
  var tipo_cuenta = $("#tipo_cuenta").val();
  var id_banco = $("#id_banco").val();
  var is_juridico = $('select[name="tipo_id"]').val();
  var prefijo_id = $("#prefijo_id").val();

  var datosIncompletos = false;

  //e.preventDefault();

  if(nombre == "" || apellido == "" || cedula == "" || telefono == "" || correo == "" || username == "" || password == "" || password_confirm == "")
  {
    datosIncompletos = true;
    Materialize.toast('Debe llenar todos los datos de usuario', 4000);   
  }

  if((titular_tarjeta != "" || ci_tarjeta != "" || num_tarjeta != "" || /*mes_venc != "" ||*/ ano_venc != "" || /*empresa_emisora != "" ||*/ direccion_tarjeta != "") && (titular_tarjeta == "" || ci_tarjeta == "" || num_tarjeta == "" || mes_venc == "" || ano_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
  {
    datosIncompletos = true;
    Materialize.toast('Debe llenar todos los campos de la tarjeta', 4000);   
  }

  if((titular_cuenta != "" || ci_cuenta != "" || num_cuenta != "" /*|| tipo_cuenta != "" || id_banco != ""*/) && (titular_cuenta == "" || ci_cuenta == "" || num_cuenta == "" || tipo_cuenta == "" || id_banco == ""))
  {
    datosIncompletos = true;
    Materialize.toast('Debe llenar todos los campos de la cuenta', 4000);   
  }

  $(".invalid").each(function()
  { 
    datosIncompletos = true;
    Materialize.toast('Debe corregir los errores del formulario', 4000);
  })

  if(datosIncompletos)
  {
    e.preventDefault();
  }
  else
  { 
    
    $.ajax({   
      url: "https://localhost/services/ServicioUsuario.php",
      type: "POST",
      data: {accion: "registrarUsuario", nombre: nombre, apellido: apellido, cedula: cedula, codigo_celular: codigo_celular, telefono: telefono, direccion: direccion,
            correo: correo, username: username, password: password, titular_tarjeta: titular_tarjeta, ci_tarjeta: ci_tarjeta,
            num_tarjeta: num_tarjeta, mes_venc: mes_venc, ano_venc: ano_venc, empresa_emisora: empresa_emisora, direccion_tarjeta: direccion_tarjeta,
            titular_cuenta: titular_cuenta, ci_cuenta: ci_cuenta, num_cuenta: num_cuenta, tipo_cuenta: tipo_cuenta, id_banco: id_banco, is_juridico: is_juridico, prefijo_id: prefijo_id},
      dataType: 'json',
      success: function(data){  
        console.log(data); 
         e.preventDefault();
        if(data.success)
        {
          Materialize.toast("Registro realizado con éxito", 4000);
          window.location="https://localhost/login.html";

        }
        else
        {
          Materialize.toast(data.message, 4000);   
          e.preventDefault();
        }
      }
    });
  }

}); 
 
