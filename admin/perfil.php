<?php
    session_start();

    if(!isset($_SESSION['usuario_sistema_id_usuario']) || empty($_SESSION['usuario_sistema_id_usuario'])){

        header('location: index.php');

    }

    if(isset($_POST['id_usuario']))
    {
        $id_usuario = $_POST['id_usuario'];
    }
    else
    {
        $id_usuario = -1;
    }
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Pagalo Facil Perfil</title>
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
  
</head>

<body class="blue-grey darken">

    <div class="containerApp row">
       <div class="box col s12 m10 offset-m1 l8 offset-l2" >

            <div class="registro">
                <!--<div class="title">Registro</div>-->
                <div class="row">
                    <div class="col s10 offset-s1 hide-on-med-and-up">
                      <img src="../images/logo.png" alt="" class="responsive-img"> 
                    </div>
                    
                    <div class="col s12 m8 l9">
                        <div class="title">Editar Perfil</div><br>
                    </div>
                    <div class="col s8 m4 l3 right-align hide-on-small-only">
                        <img src="../images/logo.png" alt="" class="responsive-img"> 
                    </div>
                </div>

                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                                
                                <input name ="id_usuario" id="id_usuario" type="hidden" value="<?=$id_usuario?>">
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="nombre" type="text" disabled>
                                <label for="nombre">Nombre</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="apellido" type="text" disabled>
                                <label for="apellido">Apellido</label>
                            </div>
                            <div class="input-field col s3 m2 l1">
                                <input id="nacionalidad" type="text" value="V" disabled>
                            </div>
                            <div class="input-field col s9 m4 l5">
                                <input id="cedula" type="text"  disabled>
                                <label for="cedula" class="hide-on-med-and-down" disabled>Cédula de identidad</label>
                                <label for="cedula" class="hide-on-large-only" disabled>C.I.</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="correo" type="email" class="validate" required="" aria-required="true">
                                <label for="correo" data-error="email inválido">Correo Electrónico</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="id_rol" type="text" disabled>
                                <!--<select id="id_rol">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                </select>-->
                                <label for="id_rol">Rol</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="username" type="text" disabled>
                                <label for="username">Usuario</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="password" type="password" class="validate" pattern="(?=.*[!\*\.])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" required="" aria-required="true">
                                <label for="password" data-error="debe contener al menos un número, una mayúscula, una minúscula, un caracter especial (: ! * .) y entre 8 y 15 caracteres en total">Contraseña</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="password_confirm" type="password" class="validate" required="" aria-required="true">
                                <label for="password_confirm" data-error="la contraseña no coincide">Repetrir Contraseña</label>
                            </div>

                        </div><br>


                        <div class="input-field col s12">
                            <a class="btn waves-effect waves-light" id="btnSubmitUserSystem" type="submit" name="action">Actualizar</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
  
    <script type="text/javascript" src='../js/jquery-2.1.3.min.js'></script>

    <!--<script src="../js/index_sys.js"></script>-->


    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script type="text/javascript" src="../js/edicionUsuarioSistema.js"></script>

</body>
</html>