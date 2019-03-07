$(document).ready(function() {

  var valid=sessionStorage.getItem("d_s");

  if(valid){
    console.log("sesion iniciada");
    cargarDatosUsuario();
    }
  else{
    console.log("NO login");    
  }
  

});

function cargarDatosUsuario(){
  $.ajax({

    url: "services/ServicioUsuario.php",
    type: "POST",
    data: {accion: "buscarDatosUsuario", id_cliente:sessionStorage.getItem("id_cliente")},
    dataType: 'json',
    success: function(data){  

      if(data.success)
      {   
        console.log("entra success login");

        $.each(data,function (key,val) {
          $('#'+key).val(val).focusin();
        });

        $.each(data,function (key) {
          $('#'+key).prop('disabled', true);
        });


        //$("#password").val(data.contrasena);
        
      }
      else
      {
        $.each(data,function (key) {
          $('#'+key).prop('disabled', false);
        });
      }

    }

  });
}


$('#btnSubmit').on('click', function(e) {  
/*
  var telefono = $("#telefono").val();
  var direccion = $("#direccion").val();
  var correo = $("#correo").val();
  var password = $("#password").val();
  var password_confirm = $("#password_confirm").val();
*/
  var datos_reclamo = {};
  datosIncompletos = false;
  
  //if(telefono == "" || correo == "" || password == "" || password_confirm == "")
  //{
  $.each($('input,select')/*.not('[disabled]')*/,function (key,val) {
    if($(val).attr('id')!=undefined&&$(val).attr('id')!=""){
      if($(val).val()==""){
          datosIncompletos = true;
          //Materialize.toast('Debe llenar los campos solicitados', 4000);
          return;
      }
      datos_reclamo[$(val).attr('id')] = $(val).val();
    }
  });

  datos_reclamo['descripcion_reclamo'] = $('#descripcion_reclamo').val();

  //}

  $(".invalid").each(function()
  { 
    datosIncompletos = true;
  });

  if(datosIncompletos)
  {
    e.preventDefault();
    Materialize.toast('Debe corregir los errores del formulario', 4000);
    return;
  }
  else
  {
    
    $.ajax({
      url: "services/ServicioUsuario.php",
      type: "POST",
      data: {
        accion: "enviarReclamo", datos_reclamo:datos_reclamo/*telefono: telefono, direccion: direccion,
        correo: correo, contrasena: password, id_cliente:sessionStorage.getItem("id_cliente")*/
      },
      dataType: 'json',
      success: function(data){  
         
         console.log(data);
         Materialize.toast(data.message, 4000); 

        if(data.success)
        {   
          console.log("reclamo enviado");

          window.location="index.html";
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