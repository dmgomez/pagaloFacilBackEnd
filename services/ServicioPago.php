<?php

/**

 * Created by PhpStorm.

 * User: carlos.duno

 * Date: 05-12-2016

 * Time: 10:49 AM

 */

header("Access-Control-Allow-Origin: *");

include_once '../database/Connection.php';



if (isset($_POST["accion"])) {

    session_start();

    $result = array('success' => true);



    switch ($_POST["accion"]) {

        case "cargarComboEmpresaEmisora":



            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $empresa_emisora = $conn->prepare("SELECT id_empresa_emisora, nombre_empresa FROM empresa_emisora");

                $empresa_emisora->execute();



                $result_empresa = $empresa_emisora->fetchAll(PDO::FETCH_ASSOC);



                $result = array('success' => true, 'result_empresa' => $result_empresa);



                $conn = null;

            }

            catch(PDOException $e)

            {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;



        case "cargarComboBancoEmisor":



            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $banco = $conn->prepare("SELECT id_banco, nombre FROM banco");

                $banco->execute();



                $result_banco = $banco->fetchAll(PDO::FETCH_ASSOC);



                $result = array('success' => true, 'result_banco' => $result_banco);



                $conn = null;

            }

            catch(PDOException $e)

            {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;



        case "registrarUsuario":



            $nombre = $_POST["nombre"];

            $apellido = $_POST["apellido"];

            $cedula = $_POST["cedula"];

            $telefono = $_POST["telefono"];

            $direccion = $_POST["direccion"];

            $correo = $_POST["correo"];

            $usernameP  = $_POST["username"];

            $passwordP  = $_POST["password"];

            $titular_tarjeta  = $_POST["titular_tarjeta"];

            $ci_tarjeta  = $_POST["ci_tarjeta"];

            $num_tarjeta  = $_POST["num_tarjeta"];

            $mes_venc  = $_POST["mes_venc"];

            $ano_venc  = $_POST["ano_venc"];

            $empresa_emisora  = $_POST["empresa_emisora"];

            $direccion_tarjeta  = $_POST["direccion_tarjeta"];

            $titular_cuenta  = $_POST["titular_cuenta"];

            $ci_cuenta  = $_POST["ci_cuenta"];

            $num_cuenta  = $_POST["num_cuenta"];

            $tipo_cuenta  = $_POST["tipo_cuenta"];

            $id_banco  = $_POST["id_banco"];



            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                $conn->beginTransaction();



                // prepare sql and bind parameters

                $cliente = $conn->prepare("INSERT INTO cliente (nombre, apellido, cedula, telefono, direccion, correo, username, contrasena) 

			    							VALUES (:nombre, :apellido, :cedula, :telefono, :direccion, :correo, :username, :password)");

                $cliente->bindParam(':nombre', $nombre);

                $cliente->bindParam(':apellido', $apellido);

                $cliente->bindParam(':cedula', $cedula);

                $cliente->bindParam(':telefono', $telefono);

                $cliente->bindParam(':direccion', $direccion);

                $cliente->bindParam(':correo', $correo);

                $cliente->bindParam(':username', $usernameP);

                $cliente->bindParam(':password', $passwordP);



                if($cliente->execute())

                {

                    if($titular_tarjeta != "")

                    {

                        $tarjeta_asociada = $conn->prepare("INSERT INTO tarjeta_asociada (nombre_titular, ci_titular, numero_tarjeta, mes_vencimiento, ano_vencimiento, id_empresa_emisora, direccion_titular) 

					    									VALUES (:titular_tarjeta, :ci_tarjeta, :num_tarjeta, :mes_venc, :ano_venc, :empresa_emisora, :direccion_tarjeta)");

                        $tarjeta_asociada->bindParam(':titular_tarjeta', $titular_tarjeta);

                        $tarjeta_asociada->bindParam(':ci_tarjeta', $ci_tarjeta);

                        $tarjeta_asociada->bindParam(':num_tarjeta', $num_tarjeta);

                        $tarjeta_asociada->bindParam(':mes_venc', $mes_venc);

                        $tarjeta_asociada->bindParam(':ano_venc', $ano_venc);

                        $tarjeta_asociada->bindParam(':empresa_emisora', $empresa_emisora);

                        $tarjeta_asociada->bindParam(':direccion_tarjeta', $direccion_tarjeta);



                        if($tarjeta_asociada->execute())

                        {

                            $cuenta_asociada = $conn->prepare("INSERT INTO cuenta_asociada (nombre_titular, ci_titular, numero_cuenta, tipo_cuenta, id_banco) 

						    									VALUES (:titular_cuenta, :ci_cuenta, :numero_tarjeta, :tipo_cuenta, :id_banco)");

                            $cuenta_asociada->bindParam(':titular_cuenta', $titular_cuenta);

                            $cuenta_asociada->bindParam(':ci_cuenta', $ci_cuenta);

                            $cuenta_asociada->bindParam(':numero_tarjeta', $numero_tarjeta);

                            $cuenta_asociada->bindParam(':tipo_cuenta', $tipo_cuenta);

                            $cuenta_asociada->bindParam(':id_banco', $id_banco);



                            if($cuenta_asociada->execute())

                            {

                                $conn->commit();

                            }

                            else

                            {

                                $conn->rollBack();

                                $result = array('success' => false, 'message' => 'r1');

                            }

                        }

                        else

                        {

                            $conn->rollBack();

                            $result = array('success' => false, 'message' => 'r2');

                        }

                    }

                    else if($titular_cuenta != "")

                    {

                        $cuenta_asociada = $conn->prepare("INSERT INTO cuenta_asociada (nombre_titular, ci_titular, numero_cuenta, tipo_cuenta, id_banco) 

					    									VALUES (:titular_cuenta, :ci_cuenta, :numero_tarjeta, :tipo_cuenta, :id_banco)");

                        $cuenta_asociada->bindParam(':titular_cuenta', $titular_cuenta);

                        $cuenta_asociada->bindParam(':ci_cuenta', $ci_cuenta);

                        $cuenta_asociada->bindParam(':numero_tarjeta', $numero_tarjeta);

                        $cuenta_asociada->bindParam(':tipo_cuenta', $tipo_cuenta);

                        $cuenta_asociada->bindParam(':id_banco', $id_banco);



                        if($cuenta_asociada->execute())

                        {

                            $conn->commit();

                        }

                        else

                        {

                            $conn->rollBack();

                            $result = array('success' => false, 'message' => 'r3');

                        }

                    }

                    else

                    {

                        $conn->commit();

                    }



                }

                else

                {

                    $conn->rollBack();

                    $result = array('success' => false, 'message' => 'r4');

                }



                $result = array('success' => true);



                $conn = null;

            }

            catch(PDOException $e)

            {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;



        case "iniciarSesion":



            session_start();



            if(!empty($_POST['user']) && !empty($_POST['pssw'])) {

                $user=$_POST['user'];

                $pssw=$_POST['pssw'];



                try {

                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                    // set the PDO error mode to exception

                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $cliente = $conn->query("SELECT * FROM cliente WHERE username = '".$user."' AND contrasena = '".$pssw."'");

                    $cliente->execute();

                    $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);



                    $flag = false;

                    /* Comprobar el número de filas que coinciden con la sentencia SELECT */

                    if ($result_cliente > 0)

                    {

                        //$result_banco = $banco->fetchAll(PDO::FETCH_ASSOC);

                        $_SESSION['session_username']=$username;

                        $flag = true;

                    }



                    $result = array('success' => true, 'flag' => $flag);



                    //$cliente = null;

                    $conn = null;

                }

                catch(PDOException $e)

                {

                    $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

                }

            }



            break;



        case "buscarUsuario":

            session_start();



            if(!empty($_POST['user'])) {

                $user=$_POST['user'];





                try {

                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                    // set the PDO error mode to exception

                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $cliente = $conn->query("SELECT * FROM cliente WHERE telefono like '%".$user."%'");

                    $cliente->execute();

                    $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);



                    $flag = false;

                    /* Comprobar el número de filas que coinciden con la sentencia SELECT */

                    if ($result_cliente > 0)

                    {

                        //$result_banco = $banco->fetchAll(PDO::FETCH_ASSOC);

                        $_SESSION['session_username']=$username;

                        $flag = true;

                    }



                    $result = array('success' => true, 'flag' => $flag);



                    //$cliente = null;

                    $conn = null;

                }

                catch(PDOException $e)

                {

                    $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

                }

            }

            break;

        case "cerrarSesion":

            session_destroy();

            $_SESSION = array();



            if(empty($_SESSION['session_username'])) {

                $result = array('success' => true, 'flag' => true);



            }

            break;



//----------------------------------------------------------------------------------------------------------------------------------------------


        case "generarFactura":

            $result = array('success' => false, 'message' => 'Error. No se pudo generar el pago');

            $receptor = $_POST["receptor"];
            $monto = $_POST["monto"];
            $detalle = $_POST["detalle"];
            $participantes = $_POST["participantes"];
            $tipo_factura = $_POST["tipo_factura"];
            $montos_colectivo = $_POST["montos_colectivo"];

            $estatus_factura = 1;
            
            $id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

            if($id_cliente == -1 && isset($_POST['id_cliente']))
            {
                $id_cliente = $_POST['id_cliente'];
            }

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $conn->beginTransaction();

                $cliente_receptor = $conn->prepare("SELECT id_cliente FROM cliente WHERE cedula = :receptor");
                $cliente_receptor->bindParam(':receptor',$_POST['receptor']);
                $cliente_receptor->execute();

                $result_receptor = $cliente_receptor->fetch(PDO::FETCH_ASSOC);
                
                if($result_receptor && count($result_receptor) > 0)
                {

                    $factura = $conn->prepare("INSERT INTO factura VALUES (DEFAULT, :id_receptor_pago, :id_emisor_pago, :monto_factura, :detalle_factura, :id_tipo_factura, :id_estatus_factura)");

                    $factura->bindParam(':id_receptor_pago', $result_receptor['id_cliente']);
                    $factura->bindParam(':id_emisor_pago', $id_cliente);
                    $factura->bindParam(':monto_factura', $monto);
                    $factura->bindParam(':detalle_factura', $detalle);
                    $factura->bindParam(':id_tipo_factura', $tipo_factura);
                    $factura->bindParam(':id_estatus_factura', $estatus_factura);
                    
                    if($factura->execute())
                    {
                        $id_factura = $conn->lastInsertId();
                        $id_estatus_transaccion = 1;

                        $participantes = explode(",", $participantes);
                        $montos_colectivo = explode(",", $montos_colectivo);
                        $boll_transaccion = true;

                        for ($i=0; $i < count($participantes); $i++) 
                        {                                 

                            $transaccion = $conn->prepare("INSERT INTO transaccion (receptor_id, emisor_id, asunto_transaccion, monto_transaccion, 
                                                                                    id_factura, id_estatus_transaccion)  
                                                            VALUES (:id_receptor, :id_emisor, :asunto_transaccion, :monto_transaccion, :id_factura, :id_estatus_transaccion)");



                            $transaccion->bindParam(':id_receptor', $result_receptor['id_cliente']);
                            $transaccion->bindParam(':id_emisor', $participantes[$i]);
                            $transaccion->bindParam(':asunto_transaccion', $detalle);
                            $transaccion->bindParam(':monto_transaccion', $montos_colectivo[$i]);
                            $transaccion->bindParam(':id_factura', $id_factura);
                            $transaccion->bindParam(':id_estatus_transaccion', $id_estatus_transaccion);

                        
                            if($transaccion->execute())
                            { 
                                $id_transaccion = $conn->lastInsertId();

                                $transaccion_activa = $conn->prepare("INSERT INTO factura_transaccion_activa VALUES (DEFAULT, :id_factura, :id_transaccion, DEFAULT)");
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                $transaccion_activa->bindParam(':id_factura', $id_factura);
                                $transaccion_activa->bindParam(':id_transaccion', $id_transaccion);

                                if(!$transaccion_activa->execute())
                                {
                                    $boll_transaccion = false;
                                    $conn->rollBack();
                                    break;
                                }
                            }
                            else
                            {
                                $boll_transaccion = false;
                                $conn->rollBack();
                                break;
                            }

                        }


                        if($boll_transaccion)
                        {
                            //$envio_email = true;
                            for ($i=0; $i < count($participantes); $i++) 
                            { 
                                $cliente_participante = $conn->prepare("SELECT * FROM cliente WHERE id_cliente = :id_participante");
                                $cliente_participante->bindParam(':id_participante', $participantes[$i]);
                                $cliente_participante->execute();

                                $result_participante = $cliente_participante->fetch(PDO::FETCH_ASSOC);
                                
                                if($result_participante && count($result_participante) > 0)
                                {
                                    $mail = "Estimado(a) " . $result_participante['nombre'] . " " . $result_participante['apellido'] . ".<br>
                                            Ha recibido una solicitud de pago colectivo. Por favor ingrese en el siguiente enlace para completar
                                            la operación: https://pagalofacil.com/orden-pago.html?i=".$id_factura;
                                    //Titulo
                                    $titulo = "Pago colectivo";
                                    //cabecera
                                    $headers = "MIME-Version: 1.0\r\n";
                                    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                                    //dirección del remitente
                                    $headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
                                    //Enviamos el mensaje a tu_dirección_email
                                    mail($result_participante['correo'],$titulo,$mail,$headers);
                                    
                                }
                            }

                            $conn->commit();
                            $_SESSION['session_id_factura']=$id_factura;
                            $result = array('success' => true, 'message' => 'Solicitud enviada con exito', 'id_factura' => $id_factura);
                        }
                        else
                        {
                            $conn->rollBack();
                            $result = array('success' => false, 'message' => 'Error. No se pudo enviar la solicitud');
                        }
                        
                    }
                
                }
                else
                {
                    $conn->rollBack();
                    $result = array('success' => false, 'message' => 'Receptor del pago no registrado');
                }


                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;

        case "buscarDatosFactura":

            $result = array('success' => false, 'message' => 'Error. No se pudo cargar la orden de pago');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;
            $id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }
            if($id_cliente == -1 && isset($_POST['id_cliente']))
            {
                $id_cliente = $_POST['id_cliente'];
            }


            try {

                $transaccion_activa = 1;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $conn->beginTransaction();

                $datosFactura =  $conn->prepare("SELECT f.id_factura, f.id_receptor_pago, CONCAT(c.nombre, ' ', c.apellido) AS nombre_receptor_pago,
                                                    c.username, f.id_emisor_pago, f.monto_factura, f.detalle_factura, f.id_tipo_factura, 
                                                    f.id_estatus_factura, ef.descripcion_estatus_factura, t.fecha AS fecha_transaccion, 
                                                    t.emisor_id AS emisor_transaccion, t.numero_tarjeta, t.nombre_titular, t.ci_titular, 
                                                    t.empresa_tarjeta, t.monto_transaccion, t.id_estatus_transaccion, et.descripcion_estatus_transaccion 
                                                FROM factura f 
                                                    INNER JOIN cliente AS c ON f.id_receptor_pago = c.id_cliente 
                                                    INNER JOIN estatus_factura AS ef ON f.id_estatus_factura = ef.id_estatus_factura 
                                                    INNER JOIN transaccion AS t ON f.id_factura = t.id_factura 
                                                    INNER JOIN estatus_transaccion AS et ON t.id_estatus_transaccion = et.id_estatus_transaccion 
                                                    INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion
                                                WHERE f.id_factura = :id_factura AND t.emisor_id = :id_cliente AND fta.activo =:transaccion_activa");

                $datosFactura->bindParam(':id_factura', $id_factura);
                $datosFactura->bindParam(':id_cliente', $id_cliente);
                $datosFactura->bindParam(':transaccion_activa', $transaccion_activa);
                $datosFactura->execute();

                $result_factura = $datosFactura->fetch(PDO::FETCH_ASSOC);
                
                if($result_factura && count($result_factura) > 0)
                {
                    $datos_pago['total_factura'] = $result_factura['monto_factura'];
                    $datos_pago['detalle_factura'] = $result_factura['detalle_factura'];
                    $datos_pago['destinatario'] = $result_factura['nombre_receptor_pago'] .' ('. $result_factura['username'] .')';
                    $datos_pago['monto_transaccion'] = $result_factura['monto_transaccion'];
                    $datos_pago['titular'] = $result_factura['nombre_titular'];
                    $datos_pago['ci_titular'] = $result_factura['ci_titular'];
                    $datos_pago['numero_tarjeta'] = $result_factura['numero_tarjeta'];
                    $datos_pago['id_estatus_transaccion'] = $result_factura['id_estatus_transaccion'];

                    $datos_cliente['id_emisor_pago'] = $result_factura['id_emisor_pago'];
                    $datos_cliente['id_emisor_transaccion'] = $result_factura['emisor_transaccion'];
                           
                    $result = array('success' => true, 'id_factura' => $id_factura, 'datos_pago' => $datos_pago, 'datos_cliente' => $datos_cliente);
                    
                }
                /*else
                {
                    $conn->rollBack();
                    $result = array('success' => false, 'message' => 'Receptor del pago no registrado');
                }*/


                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;


        case "buscarDatosParticipante":

            $result = array('success' => false, 'message' => 'Error. No se pudo cargar los participantes');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }
            

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $conn->beginTransaction();

                $transaccion_activa = 1;

                $datosParticipantes =  $conn->prepare("SELECT t.emisor_id, CONCAT(c.nombre, ' ', c.apellido) AS nombre_receptor_pago, c.username,  
                                                    c.correo, t.monto_transaccion, t.id_estatus_transaccion, et.descripcion_estatus_transaccion 
                                                FROM transaccion t 
                                                    INNER JOIN cliente AS c ON t.emisor_id = c.id_cliente 
                                                    INNER JOIN estatus_transaccion AS et ON t.id_estatus_transaccion = et.id_estatus_transaccion 
                                                    INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion
                                                WHERE t.id_factura = :id_factura AND activo = :transaccion_activa");

                $datosParticipantes->bindParam(':id_factura', $id_factura);
                $datosParticipantes->bindParam(':transaccion_activa', $transaccion_activa);
                $datosParticipantes->execute();

                $result_participante = $datosParticipantes->fetchAll(PDO::FETCH_ASSOC);
                
                if($result_participante && count($result_participante) > 0)
                {
                    $tab_participante = '';
                    
                    foreach ($result_participante as $key => $value) {

                        switch ($value['id_estatus_transaccion']) {
                            case 1:
                                $icon = 'query_builder';
                                break;

                            case 2:
                                $icon = 'done_all';
                                break;

                            case 3:
                                $icon = 'clear';
                                break;

                            case 4:
                                $icon = 'block';
                                break;

                            case 5:
                                $icon = 'done';
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                        
                       
                        /*$result .= '            
                        <li class="collection-item avatar">
                          <i class="material-icons circle">person</i>
                          <span class="title">'.$value['username'].'</span>
                          '.$value['correo'].'
                          
                          <a href="#!" id="'.$value['cedula'].'" class="secondary-content cliente-favorito"><i class="material-icons">add_box</i></a>
                        </li>';*/
                        $tab_participante .= '
                        <div class="col s12 m12 l12" id="p_'.$value['emisor_id'].'">
                            <div class="card-panel grey lighten-5 z-depth-1" style="padding-top: 3px; padding-bottom: 0px;">
                                <div class="row valign-wrapper">
                                    <div class="col s2 m1 l1 hide-on-small-only">
                                        <img src="images/fondo1.png" alt="" class="circle responsive-img "> <!-- notice the "circle" class -->
                                    </div>
                                    <div class="col s6 m5 l4">
                                        <span class="black-text">
                                            <b>'.$value['username'].'</b>'.
                                            '<br><p class="hide-on-small-only">'.$value['correo']./*'
                                            <a href="#!" id="eliminar_p_'.$id_participante.'" class="secondary-content eliminar-participante"><i class="material-icons">delete</i></a>'.*/
                                            
                                                '</p><input id="monto_p_'.$value['emisor_id'].'" value="'.number_format($value['monto_transaccion'], 2, ',', '.').'" type="text" disabled >
                                                   
                                        </span>
                                    </div>
                                    <div class="col s4 m6 l7">
                                        
                                        <div class="chip" id="estado_p_'.$value['emisor_id'].'">
                                            '.$value['descripcion_estatus_transaccion'].'
                                            <!--<i class="material-icons" style="margin: 2.5px;">'.$icon.'</i>-->
                                        </div>
                                        <div style="float:right;">
                                            <a href="#modal1" id="reasignar_'.$value['emisor_id'].'" class="secondary-content reasignar-solicitud btn-floating disabled" onClick="reasignarSolicitud('.$value['emisor_id'].')"><i class="material-icons">repeat</i></a> &nbsp;
                                            <a href="#!" id="reenviar_'.$value['emisor_id'].'" class="secondary-content reenviar-solicitud btn-floating disabled" onClick="reenviarSolicitud('.$value['emisor_id'].')"><i class="material-icons">reply</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>';
                        
                    }

                           
                    $result = array('success' => true, 'id_factura' => $id_factura, 'tab_participante' => $tab_participante);
                    
                }
                /*else
                {
                    $conn->rollBack();
                    $result = array('success' => false, 'message' => 'Receptor del pago no registrado');
                }*/


                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;


        case "actualizarEstadoAceptar":

            $result = array('success' => false, 'message' => 'No se pudo aceptar la solicitud');

            $datos_tdc = $_POST["datos_tdc"];
            $tdc = $datos_tdc["comboTDC"];
            $cvv = $datos_tdc["cvv"];

            $id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

            if($id_cliente == -1 && isset($_POST['id_cliente']))
            {
                $id_cliente = $_POST['id_cliente'];
            }


            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }
            

            try {

                $estatus_aceptar = 5;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $datosTarjeta =  $conn->prepare("SELECT numero_tarjeta, nombre_titular, mes_vencimiento, ano_vencimiento, ci_titular,
                                                        empresa_emisora_id_empresa_emisora
                                                FROM tarjeta_asociada
                                                WHERE id_tarjeta_asociada = :id_tarjeta");

                $datosTarjeta->bindParam(':id_tarjeta', $tdc);
                $datosTarjeta->execute();

                $result_tarjeta = $datosTarjeta->fetch(PDO::FETCH_ASSOC);

                $transaccion_activa = 1;

                $transaccion =  $conn->prepare("UPDATE transaccion, factura_transaccion_activa 
                                                SET transaccion.id_estatus_transaccion = :id_estatus, transaccion.numero_tarjeta = :numero_tarjeta,
                                                    transaccion.nombre_titular = :nombre_titular, transaccion.ci_titular = :ci_titular,
                                                    transaccion.empresa_tarjeta = :empresa_tarjeta, transaccion.mes_vencimiento = :mes_vencimiento, 
                                                    transaccion.ano_vencimiento = :ano_vencimiento, 
                                                    transaccion.id_transaccion = LAST_INSERT_ID(transaccion.id_transaccion)
                                                WHERE transaccion.id_transaccion = factura_transaccion_activa.id_transaccion AND
                                                    transaccion.emisor_id = :id_cliente AND transaccion.id_factura = :id_factura AND
                                                    factura_transaccion_activa.activo = :transaccion_activa");
                //FALTAN GUARDAR DATOS DE LA TARJETA

                $transaccion->bindParam(':id_estatus', $estatus_aceptar);
                $transaccion->bindParam(':numero_tarjeta', $result_tarjeta['numero_tarjeta']);
                $transaccion->bindParam(':nombre_titular', $result_tarjeta['nombre_titular']);
                $transaccion->bindParam(':ci_titular', $result_tarjeta['ci_titular']);
                $transaccion->bindParam(':empresa_tarjeta', $result_tarjeta['empresa_emisora_id_empresa_emisora']);
                $transaccion->bindParam(':mes_vencimiento', $result_tarjeta['mes_vencimiento']);
                $transaccion->bindParam(':ano_vencimiento', $result_tarjeta['ano_vencimiento']);
                $transaccion->bindParam(':id_cliente', $id_cliente);
                $transaccion->bindParam(':id_factura', $id_factura);
                $transaccion->bindParam(':transaccion_activa', $transaccion_activa);
                //FALTA ID_TARJETA EN BD

                if($transaccion->execute())
                {
                    $tabla = "transaccion"; 
                    $id_tabla = $conn->lastInsertId();

                    $configuracion_tmp = $conn->prepare("INSERT INTO configuracion_tmp VALUES (DEFAULT, :codigo, :tabla, :id_tabla, :id_cliente)");

                    $configuracion_tmp->bindParam(':codigo', $cvv);
                    $configuracion_tmp->bindParam(':tabla', $tabla);
                    $configuracion_tmp->bindParam(':id_tabla', $id_tabla);
                    $configuracion_tmp->bindParam(':id_cliente', $id_cliente);
                     
                    if($configuracion_tmp->execute())
                    {
                        /*$datosEmisor =  $conn->prepare("SELECT f.id_emisor_pago, t.emisor_id AS emisor_transaccion
                                                        FROM factura f 
                                                            INNER JOIN transaccion AS t ON f.id_factura = t.id_factura
                                                        WHERE f.id_factura = :id_factura AND t.emisor_id = :id_cliente");

                        $datosEmisor->bindParam(':id_factura', $id_factura);
                        $datosEmisor->bindParam(':id_cliente', $id_cliente);
                        $datosEmisor->execute();

                        $result_emisor = $datosEmisor->fetch(PDO::FETCH_ASSOC);*/
                                                    
                        $result = array('success' => true, /*'result_emisor' => $result_emisor,*/ 'message' => 'Solicitud aceptada');
                    }
                    
                }
                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;


        case "actualizarEstadoDenegar":

            $result = array('success' => false, 'message' => 'No se pudo denegar la solicitud');

            $id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

            if($id_cliente == -1 && isset($_POST['id_cliente']))
            {
                $id_cliente = $_POST['id_cliente'];
            }


            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }
            

            try {

                $estatus_denegar = 4;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $transaccion_activa = 1;

                $transaccion =  $conn->prepare("UPDATE transaccion, factura_transaccion_activa SET transaccion.id_estatus_transaccion = :id_estatus 
                                                WHERE transaccion.id_transaccion = factura_transaccion_activa.id_transaccion AND
                                                        transaccion.emisor_id = :id_cliente AND transaccion.id_factura = :id_factura AND
                                                        factura_transaccion_activa.activo = :transaccion_activa");

                $transaccion->bindParam(':id_estatus', $estatus_denegar);
                $transaccion->bindParam(':id_cliente', $id_cliente);
                $transaccion->bindParam(':id_factura', $id_factura);
                $transaccion->bindParam(':transaccion_activa', $transaccion_activa);
                
                if($transaccion->execute())
                {
                                                
                    $result = array('success' => true, 'message' => 'Solicitud denegada');
                    
                }
                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;




        case "actualizarEstadoParticipante":

            $result = array('success' => false, 'message' => 'No se pudo actualizar los estados');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }
            

            try {

                $transaccion_activa = 1;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $estadoParticipantes =  $conn->prepare("SELECT t.id_transaccion, t.id_factura, t.emisor_id, t.id_estatus_transaccion, 
                                                            et.descripcion_estatus_transaccion 
                                                        FROM transaccion t 
                                                        INNER JOIN estatus_transaccion AS et ON t.id_estatus_transaccion = et.id_estatus_transaccion 
                                                        INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion 
                                                        WHERE t.id_factura = :id_factura AND fta.activo = :transaccion_activa");

                $estadoParticipantes->bindParam(':id_factura', $id_factura);
                $estadoParticipantes->bindParam(':transaccion_activa', $transaccion_activa);
                $estadoParticipantes->execute();

                $result_estado = $estadoParticipantes->fetchAll(PDO::FETCH_ASSOC);
                
                if($result_estado && count($result_estado) > 0)
                {                                        

                    $result = array('success' => true, 'result_estado' => $result_estado);
                    
                }
                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;



        case "reenviarSolicitud":

            $result = array('success' => false, 'message' => 'No se pudo reenviar la solicitud');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }

            $id_cliente = $_POST["id_cliente"];
            

            try {

                $transaccion_activa = 1;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $estadoParticipante =  $conn->prepare("SELECT *
                                                FROM transaccion t 
                                                    INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion
                                                WHERE t.id_factura = :id_factura AND t.emisor_id = :id_emisor AND fta.activo = :transaccion_activa");

                $estadoParticipante->bindParam(':id_factura', $id_factura);
                $estadoParticipante->bindParam(':id_emisor', $id_cliente);
                $estadoParticipante->bindParam(':transaccion_activa', $transaccion_activa);
                $estadoParticipante->execute(); 

                $result_estado = $estadoParticipante->fetch(PDO::FETCH_ASSOC);
                
                if($result_estado && count($result_estado) > 0)
                {
                    $conn->beginTransaction();

                    $estatus_pendiente = 1;
                    $solicitud = true;
                    $id_transaccion = $result_estado['id_transaccion'];

                    if($result_estado['id_estatus_transaccion'] == 4) 
                    {
                        $transaccion =  $conn->prepare("UPDATE transaccion SET id_estatus_transaccion = :id_estatus 
                                                        WHERE id_transaccion = :id_transaccion/*emisor_id = :id_emisor AND id_factura = :id_factura*/");

                        $transaccion->bindParam(':id_estatus', $estatus_pendiente);
                        $transaccion->bindParam(':id_transaccion', $id_transaccion);
                        /*$transaccion->bindParam(':id_factura', $id_factura);
                        $transaccion->bindParam(':id_emisor', $id_cliente);*/


                        if($transaccion->execute())
                        {
                            $result = array('success' => true, 'message' => 'Solicitud reenviada con exito.');
                            $conn->commit();
                        }
                        else
                        {
                            $solicitud = false;
                        }
                    }   
                    else if($result_estado['id_estatus_transaccion'] == 3) 
                    {
                        $no_activo = 0;

                        $desactivar_transaccion = $conn->prepare("UPDATE factura_transaccion_activa SET activo = :no_activo 
                                                                    WHERE id_transaccion = :id_transaccion");

                        $desactivar_transaccion->bindParam(':no_activo', $no_activo);
                        $desactivar_transaccion->bindParam(':id_transaccion', $id_transaccion);

                        if($desactivar_transaccion->execute())
                        {

                            $transaccion = $conn->prepare("INSERT INTO transaccion (receptor_id, emisor_id, asunto_transaccion, monto_transaccion, 
                                                                                    id_factura, id_estatus_transaccion) 
                                                            VALUES (:id_receptor, :id_emisor, :asunto_transaccion, :monto_transaccion, :id_factura, :id_estatus_transaccion)");

                            $transaccion->bindParam(':id_receptor', $result_estado['receptor_id']);
                            $transaccion->bindParam(':id_emisor', $result_estado['emisor_id']);
                            $transaccion->bindParam(':asunto_transaccion', $result_estado['asunto_transaccion']);
                            $transaccion->bindParam(':monto_transaccion', $result_estado['monto_transaccion']);
                            $transaccion->bindParam(':id_factura', $result_estado['id_factura']);
                            $transaccion->bindParam(':id_estatus_transaccion', $estatus_pendiente);

                             
                            if($transaccion->execute())
                            {
                                $id_transaccion = $conn->lastInsertId();

                                $transaccion_activa = $conn->prepare("INSERT INTO factura_transaccion_activa VALUES (DEFAULT, :id_factura, :id_transaccion, DEFAULT)");
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                $transaccion_activa->bindParam(':id_factura', $result_estado['id_factura']);
                                $transaccion_activa->bindParam(':id_transaccion', $id_transaccion);

                                if($transaccion_activa->execute())
                                {
                                    $result = array('success' => true, 'message' => 'Solicitud reenviada con exito.');
                                    $conn->commit();

                                }
                            }
                            else
                            {
                                $solicitud = false;
                                $conn->rollBack();
                            }
                        }
                        else
                        {
                            $solicitud = false;
                            $conn->rollBack();
                        }
                    }

                    if($solicitud)
                    {
                        $cliente = $conn->prepare("SELECT * FROM cliente WHERE id_cliente = :id_cliente");
                        $cliente->bindParam(':id_cliente', $id_cliente);
                        $cliente->execute();

                        $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);
                        
                        if($result_cliente && count($result_cliente) > 0)
                        {
                            $mail = "Estimado(a) " . $result_cliente['nombre'] . " " . $result_cliente['apellido'] . ".<br>
                                    Ha recibido una solicitud de pago colectivo. Por favor ingrese en el siguiente enlace para completar
                                    la operación: https://pagalofacil.com/orden-pago.html?i=".$id_factura;
                            //Titulo
                            $titulo = "Pago colectivo";
                            //cabecera
                            $headers = "MIME-Version: 1.0\r\n";
                            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                            //dirección del remitente
                            $headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
                            //Enviamos el mensaje a tu_dirección_email
                            mail($result_cliente['correo'],$titulo,$mail,$headers);
                            
                        }
                    }
                    
                }                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;



        case "reasignarSolicitud":

            $result = array('success' => false, 'message' => 'No se pudo reasignar la solicitud');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }

            $id_cliente_reasignar = $_POST["id_cliente_reasignar"];
            $id_cliente_reasignado = $_POST["id_cliente_reasignado"];
            

            try {

                $transaccion_activa = 1;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $estadoParticipante =  $conn->prepare("SELECT *
                                                FROM transaccion t 
                                                    INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion
                                                WHERE t.id_factura = :id_factura AND t.emisor_id = :id_emisor AND fta.activo = :transaccion_activa");

                $estadoParticipante->bindParam(':id_factura', $id_factura);
                $estadoParticipante->bindParam(':id_emisor', $id_cliente_reasignado);
                $estadoParticipante->bindParam(':transaccion_activa', $transaccion_activa);
                $estadoParticipante->execute(); 

                $result_estado = $estadoParticipante->fetch(PDO::FETCH_ASSOC);
                
                if($result_estado && count($result_estado) > 0)
                {
                    $conn->beginTransaction();

                    $estatus_pendiente = 1;
                    $solicitud = true;
                    $id_transaccion = $result_estado['id_transaccion'];


                    $no_activo = 0;

                    $desactivar_transaccion = $conn->prepare("UPDATE factura_transaccion_activa SET activo = :no_activo 
                                                                WHERE id_transaccion = :id_transaccion");

                    $desactivar_transaccion->bindParam(':no_activo', $no_activo);
                    $desactivar_transaccion->bindParam(':id_transaccion', $id_transaccion);

                    if($desactivar_transaccion->execute())
                    {
                        $transaccion = $conn->prepare("INSERT INTO transaccion (receptor_id, emisor_id, asunto_transaccion, monto_transaccion, 
                                                                                id_factura, id_estatus_transaccion)  
                                                        VALUES (:id_receptor, :id_emisor, :asunto_transaccion, :monto_transaccion, :id_factura, :id_estatus_transaccion)");

                        $transaccion->bindParam(':id_receptor', $result_estado['receptor_id']);
                        $transaccion->bindParam(':id_emisor', $id_cliente_reasignar);
                        $transaccion->bindParam(':asunto_transaccion', $result_estado['asunto_transaccion']);
                        $transaccion->bindParam(':monto_transaccion', $result_estado['monto_transaccion']);
                        $transaccion->bindParam(':id_factura', $result_estado['id_factura']);
                        $transaccion->bindParam(':id_estatus_transaccion', $estatus_pendiente);

                        if($transaccion->execute())
                        {
                            $id_transaccion = $conn->lastInsertId();

                            $transaccion_activa = $conn->prepare("INSERT INTO factura_transaccion_activa VALUES (DEFAULT, :id_factura, :id_transaccion, DEFAULT)");

                            $transaccion_activa->bindParam(':id_factura', $result_estado['id_factura']);
                            $transaccion_activa->bindParam(':id_transaccion', $id_transaccion);

                            if($transaccion_activa->execute())
                            {
                                $result = array('success' => true, 'message' => 'Solicitud reasignada con exito.');
                                $conn->commit();

                            }
                        }
                        else
                        {
                            $solicitud = false;
                            $conn->rollBack();
                        }

                    }
                    else
                    {
                        $solicitud = false;
                        $conn->rollBack();
                    }



                    if($solicitud)
                    {
                        $cliente = $conn->prepare("SELECT * FROM cliente WHERE id_cliente = :id_cliente");
                        $cliente->bindParam(':id_cliente', $id_cliente);
                        $cliente->execute();

                        $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);
                        
                        if($result_cliente && count($result_cliente) > 0)
                        {
                            $mail = "Estimado(a) " . $result_cliente['nombre'] . " " . $result_cliente['apellido'] . ".<br>
                                    Ha recibido una solicitud de pago colectivo. Por favor ingrese en el siguiente enlace para completar
                                    la operación: https://pagalofacil.com/orden-pago.html?i=".$id_factura;
                            //Titulo
                            $titulo = "Pago colectivo";
                            //cabecera
                            $headers = "MIME-Version: 1.0\r\n";
                            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                            //dirección del remitente
                            $headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
                            //Enviamos el mensaje a tu_dirección_email
                            mail($result_cliente['correo'],$titulo,$mail,$headers);
                            
                        }
                    }
                    
                }                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;


        case "reasignarParticipante":

            $result = array('success' => false, 'message' => 'No se pudo reasignar la solicitud');

            $id_factura = isset($_SESSION['session_id_factura']) ? $_SESSION['session_id_factura'] : -1;

            if($id_factura == -1 && isset($_POST['id_factura']))
            {
                $id_factura = $_POST['id_factura'];
            }

            $id_cliente = $_POST["id_cliente"];
            

            try {

                $transaccion_activa = 1;

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $participante =  $conn->prepare("SELECT t.emisor_id, CONCAT(c.nombre,' ', c.apellido) AS nombre, username, correo
                                                FROM transaccion t 
                                                INNER JOIN cliente c ON t.emisor_id = c.id_cliente
                                                INNER JOIN factura_transaccion_activa AS fta ON t.id_transaccion = fta.id_transaccion
                                                WHERE t.id_factura = :id_factura AND t.emisor_id <> :id_emisor AND fta.activo =:transaccion_activa");

                $participante->bindParam(':id_factura', $id_factura);
                $participante->bindParam(':id_emisor', $id_cliente);
                $participante->bindParam(':transaccion_activa', $transaccion_activa);
                $participante->execute(); 

                $result_participante = $participante->fetchAll(PDO::FETCH_ASSOC);
                
                if($result_participante && count($result_participante) > 0)
                {
                    $tab_participante = '';

                    foreach ($result_participante as $key => $value) {

                        $tab_participante .= '
                        <div class="col s12 m12 l12" id="m_'.$value['emisor_id'].'">
                            <div class="card-panel grey lighten-5 z-depth-1" style="padding-top: 3px; padding-bottom: 0px;">
                                <div class="row valign-wrapper">
                                    <div class="col s2 m1 l1 hide-on-small-only">
                                        <img src="images/fondo1.png" alt="" class="circle responsive-img "> <!-- notice the "circle" class -->
                                    </div>
                                    <div class="col s6 m5 l4">
                                        <span class="black-text">
                                            <b>'.$value['username'].'</b>'.
                                            '<p class="hide-on-small-only">'.$value['correo']./*'
                                            <a href="#!" id="eliminar_p_'.$id_participante.'" class="secondary-content eliminar-participante"><i class="material-icons">delete</i></a>'.*/
                                            
                                                '</p>
                                                   
                                        </span>
                                    </div>
                                    <div class="col s4 m6 l7">
                                        
                                        <div style="float:right;">
                                            <a href="#modal1" id="s_reasignar_'.$value['emisor_id'].'" class="secondary-content reasignar-participante btn-floating" onClick="reasignarSolicitudParticipante('.$value['emisor_id'].','.$id_cliente.')"><i class="material-icons">check</i></a> &nbsp;
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

                    }

                           
                    $result = array('success' => true, 'id_factura' => $id_factura, 'tab_participante' => $tab_participante);
                    
                    
                }                
                
            }
            catch(PDOException $e)
            {
                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
            }

            break;


    }





}

else

{

    $result = array('success' => false, 'message' => "Acción no definida");

}

echo json_encode($result);

?>