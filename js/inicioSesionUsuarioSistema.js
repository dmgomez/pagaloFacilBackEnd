$('#btnAceptarUsuarioSistema').on('click', function(e) {  

  var user = $("#user").val();
  var pssw = $("#password").val();

  //console.log("entra click");
  if (grecaptcha.getResponse() === "") {
    Materialize.toast("Verifique que ud no es un robot", 4000);
  }else {
    $.ajax({
      url: "https://pagalofacil.com/services/ServicioUsuarioSistema.php",
      type: "POST",
      data: {accion: "iniciarSesionUsuarioSistema", user: user, pssw: pssw,recaptcha:grecaptcha.getResponse()},
      dataType: 'json',
      success: function (data) {

        console.log(data);

        if (data.success) {
          console.log("entra success login");

          //if (data.flag) {

          //  console.log("entra true login");

            window.location = "https://pagalofacil.com/admin/admin_transaction_list.php";
            //window.location="https://pagalofacil.com/admin/perfil.php";

         /* }
          else {
            Materialize.toast(data.message, 4000);

          }*/

        }

        else {
          Materialize.toast(data.message, 4000);
         // Materialize.toast("Debe llenar los datos de acceso", 4000);

        }

      }

    });
  }
}); 