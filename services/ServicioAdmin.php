<?php

header("Access-Control-Allow-Origin: *");

include_once '../database/Connection.php';



session_start();



if(!isset($_SESSION['usuario_sistema_id_usuario'],$_SESSION['usuario_sistema_nombre'],$_SESSION['usuario_sistema_rol'])){

    echo json_encode(['success' => false, 'message' => 'Error, no tienes permisos para acceder a &eacute;sta secci&oacute;n','data'=>[0,1,2,3,4,5,6,7]]);

    return;

}



if (isset($_POST["accion"])) {



    $result = array('success' => true);



    switch ($_POST["accion"]) {
        case "archivoList":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $listado_trans = $conn->prepare("SELECT 

                                                   sum(tr.monto_transaccion) as cantidad,rc.cedula as cedula,CONCAT(rc.nombre,\" \",rc.apellido) as emisor, rc.numero_cuenta as numero_de_cuenta,

                                                   COALESCE(sts.estado,'Aprobada') AS estado

                                                    FROM transaccion AS tr

                                                      LEFT JOIN cliente em ON tr.emisor_id = em.id_cliente

                                                      LEFT JOIN cliente rc ON tr.receptor_id = rc.id_cliente

                                                      LEFT JOIN (SELECT g3.id_transaccion  AS tra, g3.id_gestion, g3.estatus_gestion AS lst

                                                                 FROM gestion AS g3

                                                                   INNER JOIN (SELECT

                                                                                 g.id_transaccion  AS tra,

                                                                                 MAX(g.id_gestion) AS mid

                                                                               FROM gestion g

                                                                               GROUP BY g.id_transaccion) AS g2 ON g2.mid = g3.id_gestion

                                                                     ) AS gest ON tr.id_transaccion = gest.tra

                                                      LEFT JOIN status sts ON gest.lst = sts.id_status
                                                    
                                                    GROUP BY rc.cedula 
                                                    ORDER BY tr.fecha");

                $listado_trans->execute();



                $result_trans = $listado_trans->fetchAll(PDO::FETCH_NUM);



                $result = array('success' => true, 'data' => $result_trans);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;
        case "listarTransacciones":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $listado_trans = $conn->prepare("SELECT tr.id_transaccion as id, CONCAT(em.nombre,\" \",em.apellido) as emisor,CONCAT(rc.nombre,\" \",rc.apellido) as receptor,

                                                   tr.monto_transaccion as cantidad, rc.numero_cuenta as numero_de_cuenta, tr.fecha as fecha,

                                                   COALESCE(sts.estado,'Pendiente') AS estado,'EDIT_BTN' AS edit_btn

                                                    FROM transaccion AS tr

                                                      LEFT JOIN cliente em ON tr.emisor_id = em.id_cliente

                                                      LEFT JOIN cliente rc ON tr.receptor_id = rc.id_cliente

                                                      LEFT JOIN (SELECT g3.id_transaccion  AS tra, g3.id_gestion, g3.estatus_gestion AS lst

                                                                 FROM gestion AS g3

                                                                   INNER JOIN (SELECT

                                                                                 g.id_transaccion  AS tra,

                                                                                 MAX(g.id_gestion) AS mid

                                                                               FROM gestion g

                                                                               GROUP BY g.id_transaccion) AS g2 ON g2.mid = g3.id_gestion

                                                                     ) AS gest ON tr.id_transaccion = gest.tra

                                                      LEFT JOIN status sts ON gest.lst = sts.id_status

                                                    

                                                    ORDER BY tr.fecha");

                $listado_trans->execute(); 



                $result_trans = $listado_trans->fetchAll(PDO::FETCH_NUM);



                $result = array('success' => true, 'data' => $result_trans);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;

        case "listarGestiones":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $listado_gest = $conn->prepare("SELECT gestion.id_gestion, gestion.nota_gestion, gestion.fecha_gestion, status.estado

                                                FROM gestion

                                                LEFT JOIN status ON gestion.estatus_gestion = status.id_status

                                                WHERE gestion.id_transaccion = :id_transaccion ORDER BY id_gestion DESC");

                $listado_gest->bindParam(':id_transaccion', $_POST['transaccion_id']);

                $listado_gest->execute();



                $result_gest = $listado_gest->fetchAll(PDO::FETCH_ASSOC);



                $result = array('success' => true, 'data' => $result_gest);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

         break;

        case "insertarGestion":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $gestion = $conn->prepare("INSERT gestion (id_transaccion,nota_gestion, estatus_gestion,usuario_id_usuario) 

                                    VALUES (:id_transaccion,:nota_gestion,:estatus_gestion,:usuario_id_usuario)");

                $gestion->bindParam(':id_transaccion', $_POST['id_transaccion']);

                $gestion->bindParam(':nota_gestion', $_POST['nota_gestion']);

                $gestion->bindParam(':estatus_gestion', $_POST['estatus_gestion']);

                $gestion->bindParam(':usuario_id_usuario',$_SESSION['usuario_sistema_id_usuario']);

                $gestion->execute();





                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

         break;

        case "listarAdminUsers":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $listado_adm = $conn->prepare("SELECT us.id_usuario AS id, us.username AS username, CONCAT(us.nombre,\" \",us.apellido) AS nombre, us.correo AS correo,

                                                        case us.rol WHEN 1 THEN 'Super Administrador' WHEN 2 THEN 'Administrador' END AS rol, us.fecha_creacion as fecha

                                                FROM

                                                  usuario_sistema AS us

                                                WHERE us.is_delete = 0 AND us.id_usuario <> {$_SESSION['usuario_sistema_id_usuario']}

                                                ORDER BY us.fecha_creacion");

                $listado_adm->execute();



                $result_adm = $listado_adm->fetchAll(PDO::FETCH_NUM);



                $result = array('success' => true, 'data' => $result_adm);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

            break;

        case "eliminarAdminUsers":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $elim_adm = $conn->prepare("UPDATE usuario_sistema

                                            SET is_delete = 1

                                            WHERE usuario_sistema.id_usuario = :id_usuario");

                $elim_adm->bindParam(':id_usuario', $_POST['id_usuario']);

                $elim_adm->execute();



                $result = array('success' => true);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

            break;


        case "listarReclamos":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $estatus_pendiente = 1;

                $listado_reclamo = $conn->prepare("SELECT r.id_reclamo as id, r.numero_reclamo as codigo, CONCAT(r.nombre_cliente,\" \",r.apellido_cliente) as cliente, cedula_cliente as cedula,

                                                  /* r.telefono_cliente as telefono, r.correo_cliente as correo,*/ DATE(r.fecha_reclamo) as fecha, r.motivo_reclamo as motivo,

                                                   COALESCE(sts.descripcion_estatus_reclamo,'Pendiente') AS estado,'EDIT_BTN' AS edit_btn

                                                    FROM reclamo AS r

                                                      LEFT JOIN estatus_reclamo sts ON r.id_estatus_reclamo = sts.id_estatus_reclamo

                                                    WHERE r.id_estatus_reclamo = :estatus_pendiente OR (r.id_usuario_sistema = :usuario_id_usuario)

                                                    ORDER BY r.fecha_reclamo");

                $listado_reclamo->bindParam(':estatus_pendiente', $estatus_pendiente);

                $listado_reclamo->bindParam(':usuario_id_usuario',$_SESSION['usuario_sistema_id_usuario']);

                $listado_reclamo->execute(); 



                $result_reclamo = $listado_reclamo->fetchAll(PDO::FETCH_NUM);



                $result = array('success' => true, 'data' => $result_reclamo);



                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }



            break;

          
          case "verReclamo":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $reclamo = $conn->prepare("SELECT r.numero_reclamo as num_reclamo, DATE(r.fecha_reclamo) as fecha, CONCAT(r.nombre_cliente,\" \",r.apellido_cliente) as cliente, 

                                                   cedula_cliente as cedula, r.telefono_cliente as telefono, r.correo_cliente as correo, r.motivo_reclamo as motivo, 

                                                   r.descripcion_reclamo as descripcion, COALESCE(sts.descripcion_estatus_reclamo,'Pendiente') AS estado

                                                    FROM reclamo AS r

                                                      LEFT JOIN estatus_reclamo sts ON r.id_estatus_reclamo = sts.id_estatus_reclamo

                                                    WHERE id_reclamo = :id_reclamo");

                $reclamo->bindParam(':id_reclamo', $_POST['reclamo_id']);

                $reclamo->execute();

                $result_reclamo = $reclamo->fetch(PDO::FETCH_ASSOC);



                $result = array('success' => true, 'data' => $result_reclamo);

                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

         break;


         case "editarReclamo":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $reclamo = $conn->prepare("SELECT r.nota_reclamo as nota_reclamo, r.id_estatus_reclamo AS estatus_reclamo

                                                    FROM reclamo AS r

                                                    WHERE id_reclamo = :id_reclamo");

                $reclamo->bindParam(':id_reclamo', $_POST['reclamo_id']);

                $reclamo->execute();

                $result_reclamo = $reclamo->fetch(PDO::FETCH_ASSOC);



                $result = array('success' => true, 'data' => $result_reclamo);

                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }

         break;


        case "actualizarReclamo":

            try {

                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $reclamo = $conn->prepare("SELECT * FROM reclamo WHERE id_reclamo = :id_reclamo");

                $reclamo->bindParam(':id_reclamo', $_POST['id_reclamo']);

                $reclamo->execute();

                $result_reclamo = $reclamo->fetch(PDO::FETCH_ASSOC);



                $gestion = $conn->prepare("UPDATE reclamo SET id_estatus_reclamo = :id_estatus_reclamo, nota_reclamo = :nota_reclamo, id_usuario_sistema = :usuario_id_usuario

                                    WHERE id_reclamo = :id_reclamo");

                $gestion->bindParam(':id_reclamo', $_POST['id_reclamo']);

                $gestion->bindParam(':nota_reclamo', $_POST['nota_reclamo']);

                $gestion->bindParam(':id_estatus_reclamo', $_POST['estatus_reclamo']);

                $gestion->bindParam(':usuario_id_usuario',$_SESSION['usuario_sistema_id_usuario']);

                $gestion->execute();

                $conn = null;


                if($result_reclamo['id_estatus_reclamo'] != $_POST['estatus_reclamo'])
                {

                  switch ($result_reclamo['id_estatus_reclamo']) {
                    
                    case '1':
                      $message = "Estimado(a)" .$result_reclamo['cliente']. "<br><br> Su reporte " . $result_reclamo['numero_reclamo'] . 
                                  " se encuenta \"Pendiente\". ";
                      break;

                    case '2':
                      $message = "Estimado(a)" .$result_reclamo['cliente']. "<br><br> Su reporte " . $result_reclamo['numero_reclamo'] . 
                                  " se encuenta \"En proceso\". Seguimos trabajando para brindarle un mejor servicio y atención.";
                      break;

                    case '3':
                      $message = "Estimado(a)" .$result_reclamo['cliente']. "<br><br> Su reporte " . $result_reclamo['numero_reclamo'] . 
                                  " ha sido resuelto.";
                      break;

                    case '4':
                      $message = "Estimado(a)" .$result_reclamo['cliente']. "<br><br> Su reporte " . $result_reclamo['numero_reclamo'] . 
                                  " no se puede resolver. Para mayor información por favor comuniquese con nuestro personal de atenciónal cliente.";
                      break;
                    
                  }

                  $mail = $message;
                  //Titulo
                  $titulo = "Estatus de reclamo";
                  //cabecera
                  $headers = "MIME-Version: 1.0\r\n";
                  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                  //dirección del remitente
                  $headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
                  $correo = $result_reclamo['correo_cliente'];
                  //Enviamos el mensaje a tu_dirección_email
                  mail($correo,$titulo,$mail,$headers);
                }



            } catch (PDOException $e) {

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