<?php
session_start();
/*    $_SESSION['usuario_sistema_id_usuario'] = 1;
    $_SESSION['usuario_sistema_nombre'] = 'Juan';*/
if(!isset($_SESSION['usuario_sistema_id_usuario'])||empty($_SESSION['usuario_sistema_id_usuario'])){
    header('location: index.php');
    return;
}

if($_SESSION['usuario_sistema_rol']!=1) {
    header('location: index.php');
    return;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">

    <title>Dashboard</title>
    <!-- Favicons-->
    <link rel="icon" href="../images/pfa.png" sizes="32x32">
    <!-- Favicons-->
    <!--    <link rel="icon" href="assets/images/favicon/favicon-32x32.png" sizes="32x32">-->
    <!-- Favicons-->
    <!--    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon/apple-touch-icon-152x152.png">-->
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <!--    <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png">-->
    <!-- For Windows Phone -->
    <!-- Favicons-->
    <link rel="icon" href="../images/pfa.png" sizes="32x32">

    <!-- CORE CSS-->
    <link href="../css/style-main.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/style-m.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link rel="stylesheet" href="../css/perfect-scrollbar.css">
    <link rel="stylesheet" href="../css/jquery-jvectormap.css">
    <style>
        .row .input-field.col.s12.m12.l12 .caret{
            display: none;
        }
        .toast_index_fix{
            z-index: 1001 !important;
        }
    </style>
</head>

<body>
<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START HEADER -->
<header id="header" class="page-topbar">
    <!-- start header nav-->
    <div class="navbar-fixed">
        <nav class="cyan">
            <div class="nav-wrapper">
                <div class="row">
                    <div class="col s3 m3 l3">
                        &nbsp;
                    </div>
                    <div class="col s7 m7 l7">
                        <div class="center-align">
                            <img style="margin-top:10px; width: 92px !important; height:40px !important" class="responsive-img valign" alt="" src="../images/logo.png">
                            <span class="flow-text">Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- end header nav-->
</header>
<!-- END HEADER -->

<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START MAIN -->
<div id="main">
    <form id="mi_perfil" class='hide' action='perfil.php' method='post' target='_blank'>
        <input type='hidden' name='id_usuario' value="<?=$_SESSION['usuario_sistema_id_usuario']?>">
    </form>
    <!-- START WRAPPER -->
    <div class="wrapper">
        <div class="col s12 m12 l12">
            <!-- Modal Structure -->
            <div id="delete_modal" class="modal modal-fixed-footer toast_index_fix">
                <div class="modal-content">
                    <h4>Eliminar usuario</h4>
                    <div class="row">
                        <div style="text-align: center;">
                            <i>&iquest;Est&aacute;s seguro de eliminar a &eacute;ste usuario?</i>
                            <h3 id="username"></h3>
                            <input type="hidden" id="del_user_id">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                    <a onclick="deleteAdmin()" href="#!" class="waves-effect waves-green btn-flat">S&iacute;, borrar</a>
                    <div class="preloader-wrapper">
                        <div class="spinner-layer spinner-red-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- START LEFT SIDEBAR NAV-->
        <aside id="left-sidebar-nav">
            <ul id="slide-out" class="side-nav fixed leftside-navigation">
                <li class="user-details cyan darken-2">
                    <div class="row">
                        <div class="col col s4 m4 l4">
                            <!--<img src="<?php //base_url()?>assets/images/avatar.jpg" alt="" class="circle responsive-img valign profile-image">-->
                        </div>
                        <div class="col col s8 m8 l8">
                            <ul id="profile-dropdown" class="dropdown-content">
                                <!--<li><a href="#"><i class="mdi-action-face-unlock"></i> Profile</a>
                                </li>-->
                                <li>
                                    <a onclick="$('form#mi_perfil').submit();"><i class="mdi-action-settings"></i> Perfil</a>
                                </li>
                                <!--                                <li><a href="#" onclick="Materialize.toast('En desarrollo..', 5000);"><i class="mdi-communication-live-help"></i> Help</a>-->
                                </li>
                                <li class="divider"></li>
                                <!--<li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a>
                                </li>-->
                                <li><a href="../services/logout.php"><i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                                </li>
                            </ul>
                            <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><i class="mdi-navigation-arrow-drop-down right white-text"></i>
                                <span class="nombre"><?=$_SESSION['usuario_sistema_nombre']?></span>
                            </a>
                            <p class="user-roal"><span class="username"></span></p>
                        </div>
                    </div>
                </li>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold transaccion"><a href="admin_transaction_list.php" class="waves-effect waves-cyan"><i class="mdi-action-swap-vert-circle"></i> Transacciones</a></li>
                        <li class="bold listado_usuarios"><a class="waves-effect waves-cyan"><i class="mdi-action-account-circle"></i> Listar usuarios</a></li>
                        <!--<li class="bold reporte" onclick="Materialize.toast('Pr&oacute;ximamente..', 5000);">
                            <a class="collapsible-header  waves-effect waves-cyan">
                                <i class="mdi-action-assessment"></i> Reportes
                            </a>
                        </li>-->
                    </ul>
                </li>
                <!--                <li class="li-hover"><div class="divider"></div></li>-->
            </ul>
            <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
        </aside>
        <!-- END LEFT SIDEBAR NAV-->

        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <br>

        <div class="row">
            <div class="col s10 m11 l11">
                <table class="data-table responsive-table display" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="hide">ID</th>
                        <th>Username</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th class="hide">ID</th>
                        <th>Username</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Fecha</th>
                    </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="col s2 m1 l1">
                <br>
                <a class="btn-floating waves-effect waves-light red" href="registro.php" target="_blank">
                    <i class="mdi-content-add activator tooltipped" data-position='top' data-delay='50' data-tooltip='Nuevo'></i>
                </a>
            </div>
        </div>
        <!-- END Wrapper -->
    </div>
    <!-- END MAIN -->
</div>


<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START FOOTER -->
<footer class="page-footer" style="height: initial !important;">
    <div class="footer-copyright">
        <div class="container">
            PagaloFacil
            Copyright © 2017 <a class="grey-text text-lighten-4" href="#" target="_blank">CreativeTrue C,A.</a> Todos los derechos reservados.
            <span class="right"> Diseñado y desarrollado por <a class="grey-text text-lighten-4" href="http://creativetrue.com">CreativeTrue</a></span>
        </div>
    </div>
</footer>
<!-- END FOOTER -->


<!-- ================================================
Scripts
================================================ -->
<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script type="text/javascript" src="../js/events-main.js"></script>
<!--scrollbar-->
<script type="text/javascript" src="../js/perfect-scrollbar.min.js"></script>
<!-- Datatables -->
<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<!--    <script type="text/javascript" src="../js/data-tables-script.js"></script>-->

<script>

    var preloader = $('.preloader-wrapper');

    function confirmDelete(id) {
        var boton_borrar = $('a[data-id="'+id+'"]');
        var username = boton_borrar.parent().parent().prev().prev().prev().prev().text();
        var nombre = boton_borrar.parent().parent().prev().prev().prev().text();;

        $('#del_user_id').val(id);
        $('#username').text('@'+username+' '+nombre);
        $('#delete_modal').modal('open');
    }

    function deleteAdmin() {

        if(preloader.hasClass('active')){
            Materialize.toast("Procesando, por favor espere..", 5000);
            return;
        }

        preloader.addClass('active');

        var id = $('#del_user_id').val();

        setTimeout(function () {
            $.ajax({
                'dataType':'json',
                'url':'../services/ServicioAdmin.php',
                'data':{
                    'accion':'eliminarAdminUsers',
                    'id_usuario':id
                },
                'type':'POST'
            }).done(function (result) {
                console.log(result);

                preloader.removeClass('active');

                if(!result.success){
                    Materialize.toast(result.message, 5000);
                    return;
                }

                Materialize.toast('&iexcl;Operación exitosa! Actualizando datos..', 5000);

                setTimeout(function () {
                    location.reload();
                },1500);

            }).fail(function () {
                preloader.removeClass('active');
                Materialize.toast("Network error..", 5000);
            });
        },500);

    }

    $(function () {
        $('.listado_usuarios').addClass('active');
        $('.modal').modal();

        $('.data-table').DataTable({
            "ajax": {
                "url":'../services/ServicioAdmin.php',
                "type":"POST",
                "data":{'accion':'listarAdminUsers'}
            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                var id=null;
                if(aData.success===false){
                    Materialize.toast(json.message, 5000);
                    return;
                }
                $(nRow).find('td:first').addClass('hide');
                id = $(nRow).find('td:first').html();
                $(nRow).find('td:last').html(
                    "<div style='text-align: center;'>"+
                    "<form class='hide' action='perfil.php' method='post' target='_blank'>"+
                    "<input type='hidden' name='id_usuario' value='"+id+"'>"+
                    "</form><a onclick='$(this).prev().submit()'  data-id='"+id+"' class='btn-floating waves-effect waves-light blue'> " +
                    "<i class='mdi-editor-mode-edit tooltipped' data-position='top' data-delay='50' data-tooltip='Cambiar'></i>"+
                    "</a>&nbsp;"+
                    "<a onclick='confirmDelete("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light red'> " +
                    "<i class='mdi-action-delete tooltipped' data-position='top' data-delay='50' data-tooltip='Eliminar'></i>"+
                    "</a>&nbsp;"+
                    "</div>"
                );
            },
            "fnDrawCallback": function () {
                $('.tooltipped').tooltip({delay: 50});
            }
        });
    });

    $('select').material_select();

    $('.sidebar-collapse').sideNav({
        edge: 'left'
    });
</script>
</body>

</html>