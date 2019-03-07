$(document).ready(function() {

	$.ajax({   
      url: "https://pagalofacil.com/services/ServicioUsuario.php",
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
      url: "https://pagalofacil.com/services/ServicioUsuario.php",
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


$('#btnSubmit').on('click', function(e) {
  
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

  var datosIncompletos = false;

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

  if(datosIncompletos)
  {
    e.preventDefault();
  }
  else
  {
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var cedula = $("#cedula").val();
    var telefono = $("#telefono").val();
    var direccion = $("#direccion").val();
    var correo = $("#correo").val();
    var username = $("#username").val();
    var password = $("#password").val();

    $.ajax({   
      url: "https://pagalofacil.com/services/ServicioUsuario.php",
      type: "POST",
      data: {accion: "registrarUsuario", nombre: nombre, apellido: apellido, cedula: cedula, telefono: telefono, direccion: direccion,
            correo: correo, username: username, password: password, titular_tarjeta: titular_tarjeta, ci_tarjeta: ci_tarjeta,
            num_tarjeta: num_tarjeta, mes_venc: mes_venc, ano_venc: ano_venc, empresa_emisora: empresa_emisora, direccion_tarjeta: direccion_tarjeta,
            titular_cuenta: titular_cuenta, ci_cuenta: ci_cuenta, num_cuenta: num_cuenta, tipo_cuenta: tipo_cuenta, id_banco: id_banco},
      dataType: 'json',
      success: function(data){  
        console.log(data);//alert("AA");
         e.preventDefault();
        if(data.success)
        {
          Materialize.toast("Registro realizado con éxito", 4000);
          window.location="https://pagalofacil.com/login.html";

        }
        else
        {
          alert(data.message);   
          e.preventDefault();
        }
      }
    });
  }

 /* if((titular_tarjeta != "" && (ci_tarjeta == "" || num_tarjeta == "" || mes_venc == "" || ano_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
    || (ci_tarjeta != "" && (titular_tarjeta != "" || num_tarjeta == "" || mes_venc == "" || ano_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
    || (num_tarjeta != "" && (titular_tarjeta != "" || ci_tarjeta == "" || mes_venc == "" || ano_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
    //|| (mes_venc != "" && (titular_tarjeta != "" || ci_tarjeta == "" || num_tarjeta == "" || ano_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
    || (ano_venc != "" && (titular_tarjeta != "" || ci_tarjeta == "" || num_tarjeta == "" || mes_venc == "" || empresa_emisora == "" || direccion_tarjeta == ""))
    || (empresa_emisora != "" && (titular_tarjeta != "" || ci_tarjeta == "" || num_tarjeta == "" || mes_venc == "" || ano_venc == "" || direccion_tarjeta == ""))
    || (direccion_tarjeta != "" && (titular_tarjeta != "" || ci_tarjeta == "" || num_tarjeta == "" || mes_venc == "" || ano_venc == "" || empresa_emisora == ""))
    )
  {
      Materialize.toast('Debe llenar todos los campos de la tarjeta', 4000);   
  }*/


}); 
