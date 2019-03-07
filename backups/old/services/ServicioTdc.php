<?php
header("Access-Control-Allow-Origin: *");
include_once '../database/Connection.php';

if (isset($_POST["accion"])) {

    $result = array('success' => true);

    switch ($_POST["accion"]) {

        case "cargarTarjetas":
             $user=$_POST["usuario"];
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $tarjetas = $conn->prepare("SELECT ta.id_tarjeta_asociada,ta.numero_tarjeta FROM tarjeta_asociada ta,cliente c WHERE ta.id_usuario_cliente=c.id_cliente and  c.telefono ='".$user."'");
                $tarjetas->execute();
                $result_tarjetas = $tarjetas->fetch(PDO::FETCH_ASSOC);
                print_r($result_tarjetas);
                //$result_tarjetas = $tarjetas->fetchAll(PDO::FETCH_ASSOC);

                $result = array('success' => true, 'result_tarjetas' => $result_tarjetas);

                $conn = null;
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