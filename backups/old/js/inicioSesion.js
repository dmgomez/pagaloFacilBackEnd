$('#btnAceptar').on('click', function(e) {  
  var user = $("#user").val();
  var pssw = $("#password").val();
  console.log("entra click");

  $.ajax({
    url: "https://pagalofacil.com/services/ServicioUsuario.php",
    type: "POST",
    data: {accion: "iniciarSesion", user: user, pssw: pssw},
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
          //sessionStorage.d_s=data.cliente.telefono;
          window.location="https://pagalofacil.com/index.html";

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
}); 