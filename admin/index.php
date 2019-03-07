<?php
session_start();

if(isset($_SESSION['usuario_sistema_id_usuario']) && !empty($_SESSION['usuario_sistema_id_usuario'])){

    header("Location: https://pagalofacil.com/admin/admin_transaction_list.php"); 

}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pagalo Facil Login</title>
  <!-- Favicons-->
  <link rel="icon" href="../images/pfa.png" sizes="32x32">
  <!-- Favicons-->
  
  <!--Import Google Icon Font-->
  <link href="../css/materialIcon.css" rel="stylesheet">
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
  <link rel="stylesheet" href="../css/style.css">

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
</head>

<body class="blue-grey darken">
  <div class="materialContainer">

    <div class="box col s12 m6 l3">

      <!--<div class="title">Inicio de sesión</div>-->
      <div class="row">
        <div class="col s10 offset-s1 hide-on-med-and-up">
          <img src="../images/logo.png" alt="" class="responsive-img"> 
        </div>
        
        <div class="col s12 m7 l7">
          <div class="title">Inicio de sesión</div><br>
        </div>
        <div class="col s8 m5 l5 right-align hide-on-small-only">
          <img src="../images/logo.png" alt="" class="responsive-img"> 
        </div>

      </div>

      <div class="row">
        <form class="col s12 m12 l12">
          <div class="row">
            <div class="input-field col s12 m12 l12">
              <i class="material-icons prefix">account_circle</i>
              <input id="user" type="text" class="validate" required="" aria-required="true">
              <label for="user">Usuario</label>
            </div>
            <div class="input-field col s12 m12 l12">
              <i class="material-icons prefix">lock</i>
              <input id="password" type="password" class="validate" required="" aria-required="true">
              <label for="password">Contraseña</label>
            </div>

          </div>
        </form>
      </div>
      <div class="row">
        <div class="col s12 center-align">
          <div class="g-recaptcha" data-sitekey="6Ld-xxAUAAAAABOFcg-7GA3zFG7-DiAatl-enCAP"></div>
        </div>
      </div>
      <div class="col s12 center-align"><a class="waves-effect waves-light btn center-align" id="btnAceptarUsuarioSistema">Aceptar</a></div>

      <a href="" class="pass-forgot">¿Olvidó su contraseña?</a>

    </div>

  </div>

  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="../js/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../js/materialize.min.js"></script>

  <script type="text/javascript" src="../js/inicioSesionUsuarioSistema.js"></script>

</body>
</html>
        