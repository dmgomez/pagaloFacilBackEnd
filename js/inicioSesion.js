$('#btnAceptar').on('click', function(e) {  

  var user = $("#user").val();
    //var pssw = CryptoJS.SHA1($("#password").val());

  var pssw = $("#password").val();
if (user === "" || pssw==="") {
    Materialize.toast("Ingrese un Usuario y contraseña por favor", 4000);
}
else{
  if (grecaptcha.getResponse() === "") {
    Materialize.toast("Verifique que ud no es un robot", 4000);
    }else{
   $.ajax({
    beforeSend: function(){
        $(".form").css("display", "none");
        $("#preloader").css("display", "");
        $("#preloader").addClass("loading");
        //$("#preloader-text").css("display", "");
        //$("#preloader-text").text("Cargando...");
    },
    complete: function(){
        $(".form").css("display", "");
        $("#preloader").removeClass("loading");
    },
    url: "https://pagalofacil.com/services/ServicioUsuario.php",

    type: "POST",

    data: {accion: "iniciarSesion", user: user, pssw: pssw,
    recaptcha:grecaptcha.getResponse()},

    dataType: 'json',

    success: function(data){  

       console.log(data);

       //e.preventDefault();

      if(data.success)

      {   console.log("entra success login");

        if(data.flag){

          console.log("entra true login");

          sessionStorage.setItem("d_s", true);

          sessionStorage.setItem("user", data.user);

          sessionStorage.setItem("id_cliente", data.id_cliente);

          //sessionStorage.d_s=data.cliente.telefono;

          var id_factura = getParameterByName('i');
          if(id_factura == -1)
          {
            window.location="https://pagalofacil.com/index.html";
          }
          else
          {
            window.location="https://pagalofacil.com/orden-pago.html?i="+id_factura;
          }



          //sessionStorage.setItem("d_s", data.flag);

        }

        else

        {

          Materialize.toast("Usuario o contraseña incorrecto", 4000);

          sessionStorage.setItem("d_s", false);

          // e.preventDefault();

        }

        

      }

      else

      {

        Materialize.toast("Usuario o contraseña incorrecto", 4000);  

        sessionStorage.setItem("d_s", false);

       // e.preventDefault();

      }

    }

  });
}
}


//document.getElementById("form_id").submit(); 
 

}); 

$('#btnRecordar').on('click', function(e) {  

  var mail = $("#correo").val();
if (mail === "") {
    Materialize.toast("Ingrese su correo por favor", 4000);
}
else{
  if (grecaptcha.getResponse() === "") {
    Materialize.toast("Verifique que ud no es un robot", 4000);
    }else{
   $.ajax({

    url: "https://pagalofacil.com/services/ServicioUsuario.php",

    type: "POST",

    data: {accion: "olvidecontraseña", mail: mail,
    recaptcha:grecaptcha.getResponse()},

    dataType: 'json',

    success: function(data){  

       console.log(data);

       //e.preventDefault();

      if(data.success)
      {   console.log("entra success login");
        Materialize.toast(data.message, 4000);

      }
      else
      {

        Materialize.toast(data.message, 4000);

       // e.preventDefault();

      }

    }

  });
}
}


//document.getElementById("form_id").submit(); 
 

}); 