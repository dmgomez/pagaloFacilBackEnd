$(document).ready(function() {

  var valid=sessionStorage.getItem("d_s");

  //alert(valid);

  if(valid == "true"){
    console.log("sesion iniciada");
    cargarDatosUsuario();
    }
  else{
    console.log("NO login");    
  }
  

});

function cargarDatosUsuario(){
  $.ajax({

    url: "https://pagalofacil.com/services/ServicioUsuario.php",
    type: "POST",
    data: {accion: "buscarDatosUsuario", id_cliente:sessionStorage.getItem("id_cliente")},
    dataType: 'json',
    success: function(data){  

      if(data.success)
      {   
        console.log("entra success login");
/*        $("#nombre").val(data.nombre);
        $("#nombre").focusin();
        $("#apellido").val(data.apellido);
        $("#apellido").focusin();
        $("#cedula").val(data.cedula);
        $("#cedula").focusin();
        $("#telefono").val(data.telefono);
        $("#telefono").focusin();
        $("#direccion").val(data.direccion);
        $("#direccion").focusin();
        $("#correo").val(data.correo);
        $("#correo").focusin();
        $("#rol").val(data.rol);
        $("#rol").focusin();
        $("#username").val(data.username);
        $("#username").focusin();*/


        $.each(data.bancos,function (key,val) {
          $('#banco_id_banco').append('<option value="'+val.id_banco+'">'+val.nombre+'</option>');
        });

        $.each(data,function (key,val) {
          $('#'+key).val(val).focusin();
        });

        if(data.is_juridico=="1")
        {
            $('#apellido').parent().addClass('hide');
            $('#nacionalidad').val('J');
            $('#nombre').parent().removeClass('l6 m6').addClass('l12 m12').find('label').html('Nombre/Razón social');
            $('#cedula').parent().find('label').html('R.I.F.');
        }

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


var password = document.getElementById("contrasena"), confirm_password = document.getElementById("contrasena_confirm");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;


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