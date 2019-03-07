<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pagalo Facil Reiniciar contraseña</title>
  <!-- Favicons-->
  <link rel="icon" href="../images/pfa.png" sizes="32x32">
  <!-- Favicons-->
  
  <!--Import Google Icon Font-->
  <link href="../css/materialIcon.css" rel="stylesheet">
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
  <link rel="stylesheet" href="../css/style.css">
  <link href="../css/preloader.css" type="text/css" rel="stylesheet" media="screen,projection">

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


 <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
</head>


<body class="blue-grey darken">
<?php
$actual_link ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$encrypt = $_GET['encrypt'];
?>
  <div class="materialContainer">

    <div class="box col s12 m6 l3">
   <form class="form" action="javascript:void(0)">
     
    <div class="row">
        <div class="col s10 offset-s1 hide-on-med-and-up">
          <img src="../images/logo.png" alt="" class="responsive-img"> 
        </div>
        
        <div class="col s12 m7 l7">
          <div class="title">Reiniciar contraseña</div><br>
        </div>
        <div class="col s8 m5 l5 right-align hide-on-small-only">
          <img src="../images/logo.png" alt="" class="responsive-img"> 
        </div>

      </div>
     

      <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="services/reset.php">
            <input type="hidden" name="token" value="<?php echo $encrypt; ?> ">
            <input type="hidden" name="action" value="reset">

            <div class="form-group">
              <label class="col-md-4 control-label">Dirección de correo</label>
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Contraseña nueva</label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password" class="validate" pattern="(?=.*[!\*\.])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" required="" aria-required="true">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Confirmar Contraseña nueva</label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password_confirmation" class="validate" pattern="(?=.*[!\*\.])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" required="" aria-required="true">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Reiniciar
                </button>
              </div>
            </div>
          </form>
   <div class="col s12 m12 l12" align="center" id="preloader" style="height: 128px;" display="none"></div>
      <!--<div class="title">Inicio de sesión</div>-->
      
    </div>

  </div>

  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="../js/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../js/materialize.min.js"></script>

  <!--<script type="text/javascript" src="js/plugins.js"></script>-->
  <script type="text/javascript" src="../js/validate_forms.js"></script>
  <script type="text/javascript" src="../js/inicioSesion.js"></script>
  <script src="../js/sha1.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      var password = document.getElementById("password"), confirm_password = document.getElementById("password_confirmation");


      function validatePassword(){
        if(password.value != confirm_password.value) {
          confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
          confirm_password.setCustomValidity('');
        }
      }

      password.onchange = validatePassword;
      confirm_password.onkeyup = validatePassword;
    }
  </script>
</body>
</html>
        