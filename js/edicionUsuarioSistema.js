$(document).ready(function() {

  /*$.ajax({   
      url: "https://pagalofacil.com/services/ServicioUsuarioSistema.php",
      type: "POST",
      data: {accion: "mostrarForm", form: 'E' },
      dataType: 'json',
      success: function(data){  
        
        
        if(!data.success)
        {
          window.location="https://pagalofacil.com/admin";
        }
        else
        {*/
          $.ajax({

            url: "https://pagalofacil.com/services/ServicioUsuarioSistema.php",
            type: "POST",
            data: {accion: "buscarUsuario", id_usuario: $("#id_usuario").val() },
            dataType: 'json',
            success: function(data){  

               console.log(data);

              if(data.success)
              {   
                console.log("entra success login");
                $("#nombre").val(data.nombre);
                $("#nombre").focusin();
                $("#apellido").val(data.apellido);
                $("#apellido").focusin();
                $("#cedula").val(data.cedula);
                $("#cedula").focusin();
                $("#correo").val(data.correo);
                $("#correo").focusin();
                $("#id_rol").val(data.rol);
                $("#id_rol").focusin();
                $("#username").val(data.username);
                $("#username").focusin();
                //$("#password").val(data.contrasena);
                
              }
              else
              {

                Materialize.toast(data.message, 4000);  

                //sessionStorage.setItem("d_s", false);

              }

            }

          });
  /*      }
      }
  });*/

  


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


$('#btnSubmitUserSystem').on('click', function(e) {  

  var correo = $("#correo").val();
  var password = $("#password").val();
  var password_confirm = $("#password_confirm").val();

  datosIncompletos = false;

  if(correo == "" || password == "" || password_confirm == "")
  {
    datosIncompletos = true;
    Materialize.toast('Debe llenar los campos requeridos', 4000);
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
      url: "https://pagalofacil.com/services/ServicioUsuarioSistema.php",
      type: "POST",
      data: {accion: "actualizarPerfil", correo: correo, contrasena: password},
      dataType: 'json',
      success: function(data){  
         
         console.log(data);
         Materialize.toast(data.message, 4000); 

        if(data.success)
        {   
          console.log("perfil actualizado");

          window.location="https://pagalofacil.com/admin/admin_transaction_list.php";
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