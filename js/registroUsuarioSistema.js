$(document).ready(function() {

  $('select').material_select();

 /* $.ajax({   
      url: "https://pagalofacil.com/services/ServicioUsuarioSistema.php",
      type: "POST",
      data: {accion: "mostrarForm", form: 'R' },
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
              data: {accion: "cargarComboRol" },
              dataType: 'json',
              success: function(data){  
                
                var $rol = $("#id_rol").empty().html(' ');

                if(data.success)
                {
                  //$rol.append($("<option></option>").attr("value","").text("Seleccione una opción").attr("disabled", true).attr("selected", true));
                   
                  $.each( data.result_rol, function( key, value ) {
                    $rol.append($("<option></option>").attr("value",value.id_rol).text(value.rol));
                  });

                  $('#id_rol [value="2"]').attr('selected',true);

                  $rol.trigger('contentChanged');

                  
                }
                else{
                  alert(data.message);
                }
              }
          });
  /*      }
      }
  });*/

  

  $('select').on('contentChanged', function() {
    // re-initialize (update)
    $(this).material_select();
  }); 
  
});

$('#btnSubmitUserSystem').on('click', function(e) {
  
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var cedula = $("#cedula").val();
    var correo = $("#correo").val();
    var rol = $("#id_rol").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var password_confirm = $("#password_confirm").val();

    datosIncompletos = false;

    if(nombre == "" || apellido == "" || cedula == "" || correo == "" || rol == "" || username == "" || password == "" || password_confirm == "")
    {
      datosIncompletos = true;
      Materialize.toast('Debe llenar todos los datos de usuario', 4000);   
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
        data: {
            accion: "registrarUsuarioSistema",
            nombre: nombre,
            apellido: apellido,
            cedula: cedula,
            correo: correo,
            rol: rol,
            username: username,
            password: password
        },
        dataType: 'json',
        success: function(data){
          console.log(data); //alert("AA"); alert(data);
           e.preventDefault();
          if(data.success)
          {
            Materialize.toast("Registro realizado con éxito", 4000);
            window.location="https://pagalofacil.com/admin";

          }
          else
          {
            e.preventDefault();
          }
        }

      });
    }

});