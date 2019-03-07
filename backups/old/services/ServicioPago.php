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
    }


}
else
{
    $result = array('success' => false, 'message' => "Acción no definida");
}
echo json_encode($result);
?>