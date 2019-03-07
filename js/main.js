/**
 * Created by carlos.duno on 15-11-2016.
 */

$( document ).ready(function() {
    iniciarventana();
 
  
          
    $('#boton-atras').click(function() {
        console.log("click");
        window.location.href="index.html";
    });

    //boton-seguir-tdc
    $('#boton-seguir-detalle').click(function() {$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();});
    $('#boton-atras-user').click(function() {$("#panel-search").show();$("#panel-detalle").hide();$("#panel-tdc").hide();});
    $('#boton-seguir-tdc').click(function() {$("#panel-tdc").show();$("#panel-search").hide();$("#panel-detalle").hide();});
    $('#boton-atras-detalle').click(function() {$("#panel-tdc").hide();$("#panel-search").hide();$("#panel-detalle").show();});
    $('#borrar-user').click(function() {
      $("#seccion-img-user").show();
      $("#listado-user").hide();
    });
  
  
    $('#boton-pagar').click(function() {
        $('#modal1').openModal();
        var dataPay = {
            name: $("#id-name").val(),
            address:$("#id-address").val(),
            phone:$("#id-phone").val()
        }
        $.ajax({
          type: "POST",
          url: "https://www.pagalofacil.com/servicios/servicio-pagos.php",
          data: dataPay,
          dataType: "json",
          success: function(datos)
          {
               //Receiving the result of search here
          }
       });
    });

$("#sample_search").keyup(function()
   {
      $("#seccion-img-user").hide();

//      $("#listado-user").show();
      $.ajax({
          type: "POST",
          url: "services/ServicioUsuario.php",
          data: {'accion':'buscarUsuario','telefono':$(this).val()},
          dataType: "json",
          success: function(datos)
          {
              console.log(datos);
               //Receiving the result of search here
          }
       });

   });
});



function iniciarventana(){
    $("#panel-search").show();
    $("#panel-detalle").hide();
    $("#panel-tdc").hide();
    $("#seccion-img-user").show();
    $("#listado-user").hide();

}