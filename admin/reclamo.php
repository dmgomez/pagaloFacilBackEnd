<?php

session_start();

/*    $_SESSION['usuario_sistema_id_usuario'] = 1;

    $_SESSION['usuario_sistema_nombre'] = 'Juan';*/

if(!isset($_SESSION['usuario_sistema_id_usuario'])||empty($_SESSION['usuario_sistema_id_usuario'])){

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

    <!-- START WRAPPER -->

    <div class="wrapper">



        <div class="col s12 m12 l12">

            <!-- Modal Structure -->

            <!--<div id="history_modal" class="modal modal-fixed-footer">

                <div class="modal-content">

                    <h4>Historial de reclamo</h4>

                    <div class="row">

                        <div style="text-align: center;">

                            <i id="mensaje_reclamo"></i>

                        </div>

                        <ul class="collection listado_gestiones"></ul>

                        <h3 id="banner_name"></h3>

                    </div>

                </div>

                <div class="modal-footer">

                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Ok</a>

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

            </div>-->



            <!-- Modal Structure -->

            <div id="manage_modal" class="modal toast_index_fix" >

                <div class="modal-content">

                    <h4>Actualizar estado de reclamo</h4>

                    <div class="row">

                        <div class="input-field col s12 m12 l12">

                            <select title="Estado" name="estatus_reclamo" id="estatus_reclamo">

                                <option value="1">Pendiente</option>

                                <option value="2">En Proceso</option>

                                <option value="3">Resuelto</option>

                                <option value="4">Sin resolver</option>

                            </select>

                            <label for="estatus_reclamo">Estado</label>

                        </div>

                        <div class="input-field col s12 m12 l12">

                            <textarea id="nota_reclamo" class="materialize-textarea" name="nota_reclamo" data-length="2000"></textarea>

                            <label for="nota_reclamo">Nota</label>

                            <input type="hidden" id="id_reclamo" value="">

                        </div>



                    </div>

                </div>

                <div class="modal-footer">

                    <a href="#!" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancelar</a>

                    <a onclick="updateReclamo($('#id_reclamo').val());" class=" modal-action waves-effect waves-block btn-flat">Actualizar</a>

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


            <!-- Modal Structure -->

            <div id="view_modal" class="modal toast_index_fix" >

                <div class="modal-content">

                    <h4>Ver reclamo</h4>

                    <div class="row">

                        <div class="input-field col s12 m6 l6">

                            <input id="num_reclamo" type="text" disabled>

                            <label for="num_reclamo">Número de reclamo</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="fecha" type="text" disabled>

                            <label for="fecha">Fecha</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="cliente" type="text" disabled>

                            <label for="cliente">Cliente</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="cedula" type="text" disabled>

                            <label for="cedula">Cédula</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="telefono" type="text" disabled>

                            <label for="telefono">Teléfono</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="correo" type="text" disabled>

                            <label for="correo">Correo</label>

                        </div>

                        <div class="input-field col s12 m12 l12">

                            <input id="motivo" type="text" disabled>

                            <label for="motivo">Motivo del reclamo</label>

                        </div>

                        <div class="input-field col s12 m12 l12">

                            <textarea id="descripcion" class="materialize-textarea" name="descripcion" disabled></textarea>

                            <label for="descripcion">Desctipcion</label>

                        </div>

                        <div class="input-field col s12 m6 l6">

                            <input id="estado" type="text" disabled>

                            <label for="estado">Estado</label>

                            <!--<input type="hidden" id="id_reclamo" value="">-->

                        </div>

                        
                    </div>

                </div>

                <div class="modal-footer">

                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Ok</a>

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

                                <li><a href="perfil.php"><i class="mdi-action-settings"></i> Perfil</a>

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

                        <li class="bold transaccion"><a class="waves-effect waves-cyan" href="admin_transaction_list.php"><i class="mdi-action-swap-vert-circle"></i> Transacciones</a></li>
                        <li class="bold transaccion"><a class="waves-effect waves-cyan"><i class="mdi-action-assignment"></i> Reclamos</a></li>

                        <?php

                        if($_SESSION['usuario_sistema_rol']==1) {

                            echo '<li class="bold listado_usuarios"><a href="admin_user_list.php"

                                                                 class="waves-effect waves-cyan"><i

                                        class="mdi-action-account-circle"></i> Listar usuarios</a></li>';

                        }

                        ?>

                        <!--<li class="bold reporte" onclick="Materialize.toast('Pr&oacute;ximamente..', 5000);">

                            <a class="collapsible-header  waves-effect waves-cyan">

                                <i class="mdi-action-assessment"></i> Reportes

                            </a>

                        </li>-->

                    </ul>

                </li>

                <!--                <li class="li-hover"><div class="divider"></div></li>-->

            </ul>

            <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect hide-on-large-only cyan">

                <i class="mdi-navigation-menu"></i>

            </a>

        </aside>

        <!-- END LEFT SIDEBAR NAV-->



        <!-- //////////////////////////////////////////////////////////////////////////// -->

        <br>



        <div class="row">

            <div class="col s12 m12 l12">

                <table class="data-table responsive-table display" cellspacing="0">

                    <thead>

                    <tr>

                        <th>ID</th>

                        <th>Num. Reclamo</th>

                        <th>Cliente</th>

                        <th>Cédula</th>

                        <!--<th>Teléfono</th>

                        <th>Correo</th>-->

                        <th>Fecha</th>

                        <th>Motivo</th>

                        <th>Estatus</th>

                        <th>Acción</th>

                    </tr>

                    </thead>

                    <tfoot>

                    <tr>

                        <th>ID</th>

                        <th>Num. Reclamo</th>

                        <th>Cliente</th>

                        <th>Cédula</th>

                        <!--<th>Teléfono</th>

                        <th>Correo</th>-->

                        <th>Fecha</th>

                        <th>Motivo</th>

                        <th>Estatus</th>

                        <th>Acción</th>

                    </tr>

                    </tfoot>

                    <tbody>



                    </tbody>

                </table>

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

<!--<script type="text/javascript" src="../js/data-tables-script.js"></script>-->



<script>

    var preloader = $('.preloader-wrapper');

    $(document).ready(function(){

        $('textarea').characterCounter();

        $('.data-table').DataTable({

            "ajax": {

                "url":'../services/ServicioAdmin.php',

                "type":"POST",

                "data":{'accion':'listarReclamos'}

            },

            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

                var id=null;

                if(aData.success===false){

                    Materialize.toast(json.message, 5000);

                    return;

                }

                console.log(aData);



                id = $(nRow).find('td:first').html();

                $(nRow).find('td:last').html(

                    "<div style='text-align: center;'>"+

                    "<a onclick='verReclamo("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light orange'> " +

                    "<i class='mdi-action-visibility tooltipped' data-position='top' data-delay='50' data-tooltip='Ver'></i>"+

                    "</a>&nbsp;"+


                    "<a onclick='editarReclamo("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light blue'> " +

                    "<i class='mdi-editor-mode-edit tooltipped' data-position='top' data-delay='50' data-tooltip='Gestionar'></i>"+

                    "</a>&nbsp;"+

                    /*"&nbsp;<a onclick='listarGestiones("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light orange'> " +

                    "<i class='mdi-action-history tooltipped' data-position='top' data-delay='50' data-tooltip='Historial'></i>"+

                    "</a>"+*/

                    "</div>"

                );

            },

            "fnDrawCallback": function () {

                $('.tooltipped').tooltip({delay: 50});

            }

        });

    });



    function updateGestion(id_transaccion) {



        var estatus_gestion = $('[name="estatus_gestion"]').val();



        if(preloader.hasClass('active')){

            Materialize.toast("Procesando, por favor espere..", 5000);

            return;

        }



        preloader.addClass('active');





        if(estatus_gestion!==null){

            setTimeout(function () {

                $.ajax({

                    'dataType':'json',

                    'url':'../services/ServicioAdmin.php',

                    'data':{

                        'accion':'insertarGestion',

                        'id_transaccion':id_transaccion,

                        'nota_gestion':$('[name="nota_gestion"]').val(),

                        'estatus_gestion':estatus_gestion

                    },

                    'type':'POST'

                }).done(function (result) {

                    console.log(result);

                    if(!result.success){

                        Materialize.toast(result.message, 5000);

                        preloader.removeClass('active');

                        return;

                    }



                    Materialize.toast('&iexcl;Operación exitosa! Actualizando datos..', 5000);



                    setTimeout(function () {

                        location.reload();

                        preloader.removeClass('active');

                    },1500);



                }).fail(function () {

                    setTimeout(function () {

                        preloader.removeClass('active');

                        Materialize.toast('Network error..', 5000);

                    },500);

                });

            },500);

        }

        else{

            setTimeout(function () {

                Materialize.toast('Debes seleccionar un estado..', 5000);

                preloader.removeClass('active');

            },500);

        }



    }

  /*  function listarGestiones(transaccion_id) {

        if(preloader.hasClass('active')){

            Materialize.toast("Procesando, por favor espere..", 5000);

            return;

        }

        var listado_gestiones = $('.listado_gestiones');

        var mensaje_gestion = $('#mensaje_gestion');

        var history_modal = $('#history_modal');


        listado_gestiones.html('');

        history_modal.modal('open');

        preloader.addClass('active');


        setTimeout(function () {

            $.ajax({

                'dataType':'json',
                'url':'../services/ServicioAdmin.php',
                'data':{
                    'accion':'listarGestiones',
                    'transaccion_id':transaccion_id
                },
                'type':'POST'
            }).done(function (result) {

                var data = result.data;

                if(!result.success){

                    preloader.removeClass('active');
                    Materialize.toast(result.message, 5000);

                    return;

                }

                mensaje_gestion.html('');


                if(!data.length){

                    mensaje_gestion.html('Disculpe, no hay un historial de gestiones para esta transacci&oacute;n');

                    history_modal.modal('open');

                    return;

                }



                $.each(data,function (key,val) {

                    listado_gestiones.append(

                        '<li class="collection-item avatar">'+

                        '<i class="material-icons circle mdi-action-assignment blue"></i>'+

                        '<span class="title">'+val.fecha_gestion+'</span>'+

                        '<p>'+val.nota_gestion+'</p>'+

                        '<a href="#!" class="secondary-content"><i class="material-icons">'+val.estado+'</i></a>'+

                        '</li>'

                    );

                });



                preloader.removeClass('active');



            }).fail(function () {

                preloader.removeClass('active');

                Materialize.toast('Network error..', 5000);

            });

        },500);

    }
*/




    function verReclamo(reclamo_id) {

        if(preloader.hasClass('active')){

            Materialize.toast("Procesando, por favor espere..", 5000);

            return;

        }

        var view_modal = $('#view_modal');
        
        view_modal.modal('open');

        preloader.addClass('active');

        setTimeout(function () {

            $.ajax({

                'dataType':'json',

                'url':'../services/ServicioAdmin.php',

                'data':{

                    'accion':'verReclamo',

                    'reclamo_id':reclamo_id

                },

                'type':'POST'

            }).done(function (result) {

                var data = result.data;

                if(!result.success){

                    preloader.removeClass('active');

                    Materialize.toast(result.message, 5000);

                    return;

                }

                
               /* if(!data.length){

                    //mensaje_gestion.html('Disculpe, no hay datos para este reclamo');
                    Materialize.toast('Error al cargar los datos', 5000);

                    view_modal.modal('open');

                    return;

                }*/

                $('#num_reclamo').val(data.num_reclamo).focusin();
                $('#fecha').val(data.fecha).focusin();
                $('#cliente').val(data.cliente).focusin();
                $('#cedula').val(data.cedula).focusin();
                $('#telefono').val(data.telefono).focusin();
                $('#correo').val(data.correo).focusin();
                $('#motivo').val(data.motivo).focusin();
                $('#descripcion').val(data.descripcion).focusin();
                $('#estado').val(data.estado).focusin();
           
                /*$.each(data,function (key,val) {
                    alert(key);
                  $('#'+key).val(val).focusin();
                });*/


                preloader.removeClass('active');



            }).fail(function () {

                preloader.removeClass('active');

                Materialize.toast('Network error..', 5000);

            });

        },500);

    }


    function editarReclamo(reclamo_id) {


        if(preloader.hasClass('active')){

            Materialize.toast("Procesando, por favor espere..", 5000);

            return;

        }

        $('#id_reclamo').val(reclamo_id);

        var manage_modal = $('#manage_modal');
        
        manage_modal.modal('open');

        preloader.addClass('active');

        setTimeout(function () {

            $.ajax({

                'dataType':'json',

                'url':'../services/ServicioAdmin.php',

                'data':{

                    'accion':'editarReclamo',

                    'reclamo_id':reclamo_id

                },

                'type':'POST'

            }).done(function (result) {

                var data = result.data;

                if(!result.success){

                    preloader.removeClass('active');

                    Materialize.toast(result.message, 5000);

                    return;

                }

                if(data.nota_reclamo != "" && data.nota_reclamo != null)
                {
                    $('#nota_reclamo').val(data.nota_reclamo).focusin();
                }
                
                $('#estatus_reclamo').val(data.estatus_reclamo).focusin();
           
                preloader.removeClass('active');



            }).fail(function () {

                preloader.removeClass('active');

                Materialize.toast('Network error..', 5000);

            });

        },500);

    }



    function updateReclamo(id_reclamo) {

        var estatus_reclamo = $('[name="estatus_reclamo"]').val();

        if(preloader.hasClass('active')){

            Materialize.toast("Procesando, por favor espere..", 5000);
            return;

        }

        preloader.addClass('active');

        if(estatus_reclamo!==null){

            setTimeout(function () {

                $.ajax({

                    'dataType':'json',
                    'url':'../services/ServicioAdmin.php',
                    'data':{

                        'accion':'actualizarReclamo',
                        'id_reclamo':id_reclamo,
                        'nota_reclamo':$('[name="nota_reclamo"]').val(),
                        'estatus_reclamo':estatus_reclamo

                    },

                    'type':'POST'

                }).done(function (result) {

                    console.log(result);

                    if(!result.success){

                        Materialize.toast(result.message, 5000);
                        preloader.removeClass('active');

                        return;

                    }

                    Materialize.toast('&iexcl;Operación exitosa! Actualizando datos..', 5000);

                    setTimeout(function () {

                        location.reload();

                        preloader.removeClass('active');

                    },1500);



                }).fail(function () {

                    setTimeout(function () {

                        preloader.removeClass('active');

                        Materialize.toast('Network error..', 5000);

                    },500);

                });

            },500);

        }

        else{

            setTimeout(function () {

                Materialize.toast('Debes seleccionar un estado..', 5000);

                preloader.removeClass('active');

            },500);

        }



    }





    $(function () {

        $('.transaccion').addClass('active');

        $('.modal').modal();

        $('.sidebar-collapse').sideNav({

            edge: 'left'

        });

    });

    $('select').material_select();

</script>

</body>



</html>