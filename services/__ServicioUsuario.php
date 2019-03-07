<?php

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
			$telefono = $_POST["codigo_celular"] ."". $_POST["telefono"];
			$direccion = $_POST["direccion"];
			$correo = $_POST["correo"];
			$usernameP  = $_POST["username"];
			$passwordP  = $_POST["password"];
			$titular_tarjeta  = ($_POST["titular_tarjeta"] != "") ? $_POST["titular_tarjeta"] : null;
			$ci_tarjeta  = ($_POST["ci_tarjeta"] != "") ? $_POST["ci_tarjeta"] : null;
			$num_tarjeta  = ($_POST["num_tarjeta"] != "") ? $_POST["num_tarjeta"] : null;
			$mes_venc  = ($_POST["mes_venc"] != "") ? $_POST["mes_venc"] : null;
			$ano_venc  = ($_POST["ano_venc"] != "") ? $_POST["ano_venc"] : null;
			$empresa_emisora  = ($_POST["empresa_emisora"] != "") ? $_POST["empresa_emisora"] : null;
			$direccion_tarjeta  = ($_POST["direccion_tarjeta"] != "") ? $_POST["direccion_tarjeta"] : null;
			$titular_cuenta  = ($_POST["titular_cuenta"] != "") ? $_POST["titular_cuenta"] : null;
			$ci_cuenta  = ($_POST["ci_cuenta"] != "") ? $_POST["ci_cuenta"] : null;
			$num_cuenta  = ($_POST["num_cuenta"] != "") ? $_POST["num_cuenta"] : null;
			$tipo_cuenta  = ($_POST["tipo_cuenta"] != "") ? $_POST["tipo_cuenta"] : null;
			$id_banco  = ($_POST["id_banco"] != "") ? $_POST["id_banco"] : null;

        	try {

			    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			    // set the PDO error mode to exception
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    $conn->beginTransaction();

			    // prepare sql and bind parameters

			    /*$cliente = $conn->prepare("INSERT INTO cliente (nombre, apellido, cedula, telefono, direccion, correo, username, contrasena, nombre_titular, numero_cuenta, ci_titular, tipo_cuenta, banco_id_banco) 
			    							VALUES (:nombre, :apellido, :cedula, :telefono, :direccion, :correo, :username, :password, :titular_cuenta, :ci_cuenta, :num_cuenta, :tipo_cuenta, :id_banco)");*/
			    //if($num_cuenta)

			    $cliente = $conn->prepare("INSERT INTO cliente VALUES (DEFAULT, DEFAULT, DEFAULT, :telefono, :direccion, :username, :contrasena, :correo, :nombre, :apellido, :cedula, :num_cuenta, :titular_cuenta, :ci_cuenta, :tipo_cuenta, DEFAULT, :id_banco, DEFAULT)");

			    $cliente->bindParam(':nombre', $nombre);
			    $cliente->bindParam(':apellido', $apellido);
			    $cliente->bindParam(':cedula', $cedula);
			    $cliente->bindParam(':telefono', $telefono);
			    $cliente->bindParam(':direccion', $direccion);
			    $cliente->bindParam(':correo', $correo);
			    $cliente->bindParam(':username', $usernameP);
			    $cliente->bindParam(':contrasena', $passwordP);
			    $cliente->bindParam(':titular_cuenta', $titular_cuenta);
			    $cliente->bindParam(':ci_cuenta', $ci_cuenta);
			    $cliente->bindParam(':num_cuenta', $num_cuenta);
			    $cliente->bindParam(':tipo_cuenta', $tipo_cuenta);
			    $cliente->bindParam(':id_banco', $id_banco);

			    if($cliente->execute())
			    {
			    	$id_cliente = $conn->lastInsertId(); 

					if($titular_tarjeta != "")
			    	{

			    		$tarjeta_asociada = $conn->prepare("INSERT INTO tarjeta_asociada (id_usuario_cliente, nombre_titular, ci_titular, numero_tarjeta, mes_vencimiento, ano_vencimiento, empresa_emisora_id_empresa_emisora, direccion_titular) 
					    									VALUES (:id_cliente, :titular_tarjeta, :ci_tarjeta, :num_tarjeta, :mes_venc, :ano_venc, :empresa_emisora, :direccion_tarjeta)");

					    $tarjeta_asociada->bindParam(':id_cliente', $id_cliente);
					    $tarjeta_asociada->bindParam(':titular_tarjeta', $titular_tarjeta);
					    $tarjeta_asociada->bindParam(':ci_tarjeta', $ci_tarjeta);
					    $tarjeta_asociada->bindParam(':num_tarjeta', $num_tarjeta);
					    $tarjeta_asociada->bindParam(':mes_venc', $mes_venc);
					    $tarjeta_asociada->bindParam(':ano_venc', $ano_venc);
					    $tarjeta_asociada->bindParam(':empresa_emisora', $empresa_emisora);
					    $tarjeta_asociada->bindParam(':direccion_tarjeta', $direccion_tarjeta);


					    if($tarjeta_asociada->execute())
					    {

					    	$conn->commit();
					    	$result = array('success' => true);

					    }
					    else
					    {

					    	$conn->rollBack();

					    	$result = array('success' => false, 'message' => 'r2');

					    }

			    	}
			    	else
			    	{
			    		$conn->commit();
					    $result = array('success' => true);
			    	}

			    }
			    else
			    {

			    	$conn->rollBack();

			    	$result = array('success' => false, 'message' => 'r4');

			    }

			    

			    $conn = null; 

			}

			catch(PDOException $e)

			{

			   $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

			}	

            

            break;



        case "iniciarSesion":



        	session_start();

$captcha = "";
    if (isset($_POST["recaptcha"]))
        $captcha = $_POST["recaptcha"];

    if (!$captcha)
        $result = array('success' => false, 'message' => "Error: fallo de verificación");
    // handling the captcha and checking if it's ok
    $secret = "6Ld-xxAUAAAAAH8-TBcdD2-h_E17_AhqgUfkvWDI";
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

    // if the captcha is cleared with google, send the mail and echo ok.
    if ($response["success"] != false) {
        // valida usuario response true
        if(!empty($_POST['user']) && !empty($_POST['pssw'])) {

                $user=$_POST['user'];

                $pssw=$_POST['pssw'];



                try {

                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $is_delete = 0;

                    $cliente = $conn->prepare("SELECT * FROM cliente WHERE username = :user AND contrasena = :pssw AND is_delete = :is_delete");
                    $cliente->bindParam(':user', $user);
			    	$cliente->bindParam(':pssw', $pssw);
			    	$cliente->bindParam(':is_delete', $is_delete);

                    $cliente->execute();

                    $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);

//print_r($result_cliente-);

                    $flag = false;

                    /* Comprobar el número de filas que coinciden con la sentencia SELECT */ 

if ($result_cliente > 0)

                    {

                        $_SESSION['session_username']=$result_cliente['cedula'];

                        $flag = true;
						$result = array('success' => true, 'flag' => $flag, 'user' =>$result_cliente['cedula']);
                    }
					else{
						$flag = false;
						$result = array('success' => false, 'flag' => $flag, 'user' =>'');
					}


                    //$cliente = null;

                    $conn = null;

                }

                catch(PDOException $e)

                {

                    $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

                }

            }
    } else {
    	$result = array('success' => false, 'message' => "Error: Advertencia posible robot");
    }

            break;

		

		case "cerrarSesion":

			//session_destroy();

			$_SESSION = array();



			if(empty($_SESSION['session_username'])) {

				$result = array('success' => true, 'flag' => true);

				

			}

			break;

		case "olvidecontraseña":

			$captcha = "";
	    if (isset($_POST["recaptcha"]))
	        $captcha = $_POST["recaptcha"];

	    if (!$captcha)
	        $result = array('success' => false, 'message' => "Error: fallo de verificación");
	    // handling the captcha and checking if it's ok
	    $secret = "6Ld-xxAUAAAAAH8-TBcdD2-h_E17_AhqgUfkvWDI";
	    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

	    // if the captcha is cleared with google, send the mail and echo ok.
	    if ($response["success"] != false) {
	        // valida usuario response true
	        if(!empty($_POST['mail'])) {

                $correo=$_POST['mail'];

                try {

                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                    // set the PDO error mode to exception

                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $cliente = $conn->query("SELECT * FROM cliente WHERE correo = '".$correo."'");

                    $cliente->execute();

                    $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);

//print_r($result_cliente-);

                    $flag = false;

                    /* Comprobar el número de filas que coinciden con la sentencia SELECT */

                    if ($result_cliente > 0)

                    {

                        //$result_banco = $banco->fetchAll(PDO::FETCH_ASSOC);

                        $clave=$result_cliente['contrasena'];
                        $mail = "Tu contraseña es :".$clave."\n ingresa a www.pagalofacil.com para iniciar sesión";
						//Titulo
						$titulo = "Recordar contraseña";
						//cabecera
						$headers = "MIME-Version: 1.0\r\n"; 
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
						//dirección del remitente 
						$headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
						//Enviamos el mensaje a tu_dirección_email 
						$bool = mail($correo,$titulo,$mail,$headers);
						if($bool){
						    echo "Mensaje enviado";
						    $result = array('success' => true, 'message' => "Te hemos enviado un mensaje por favor revisa tu correo");


						}else{
						     $result = array('success' => false, 'message' => "No se pudo procesar su petición");
						}

                        $flag = true;

                    }
                    else{
                    		 $result = array('success' => false, 'message' => "Correo no valido");
                    }

                    $conn = null;

                }

                catch(PDOException $e)

                {

                    $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

                }

            }
    } else {
    	$result = array('success' => false, 'message' => "Error: Advertencia posible robot");
    }
			
			break;

//"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""


		case "buscarUsuario":

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$_POST['telefono'] = isset($_POST['telefono']) ? $_POST['telefono']."%" : '';


			$user_phone = isset($_SESSION['session_username']) ? $_SESSION['session_username'] : '';

			$usuario = $conn->prepare("SELECT cedula FROM cliente WHERE cedula like :telefono AND cedula <> '$user_phone'  LIMIT 10");



			$usuario->bindParam(':telefono',$_POST['telefono']);

			$usuario->execute();



			$result = $usuario->fetchAll(PDO::FETCH_ASSOC);



			if(!count($result)){

				$result = [["telefono"=>"No hay resultados"]];

			}



		break;


		case "buscarDatosUsuario":

			session_start(); 

			$result = array('success' => false, 'message' => 'No fue posible cargar los datos');

			$is_delete = 0;
			//$id_cliente_editar = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : -1;
			$id_cliente_editar = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$_SESSION['session_cliente_id_cliente_editar'] = $id_cliente_editar;

			$usuario = $conn->prepare("SELECT nombre, apellido, cedula, telefono, direccion, correo, username FROM cliente 
										WHERE id_cliente = :id_cliente AND is_delete = :is_delete");
			$usuario->bindParam(':id_cliente', $id_cliente_editar);
			$usuario->bindParam(':is_delete', $is_delete);
			$usuario->execute();

			$result_user = $usuario->fetch(PDO::FETCH_ASSOC);

	        /* Comprobar el número de filas que coinciden con la sentencia SELECT */

	        if ($result_user > 0)
	        {
	            $result = array(
	            	'success' => true, 
	            	'nombre' => $result_user['nombre'], 
	            	'apellido' => $result_user['apellido'],
	            	'cedula' => $result_user['cedula'],
	            	'telefono' => $result_user['telefono'],
	            	'direccion' => $result_user['direccion'],
	            	'correo' => $result_user['correo'],
	            	'username' => $result_user['username'],
	            	//'contrasena' => $result_user['contrasena']
	            );

	        }
	        else
	        {
	        	$result = array('success' => false, 'message' => 'Usuario no encontrado.');
	        }

			break;


		case "actualizarPerfil":

			session_start();
			
			$result = array('success' => false, 'message' => 'Error. No se puede actualizar el perfil');

			$telefono = $_POST["telefono"];
			$direccion = $_POST["direccion"];
			$correo = $_POST["correo"];
			$contrasena  = $_POST["contrasena"];
			//$id_user = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : -1;
			$id_user = isset($_SESSION['session_cliente_id_cliente_editar']) ? $_SESSION['session_cliente_id_cliente_editar'] : -1;

        	try {

			    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			    // set the PDO error mode to exception
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    // prepare sql and bind parameters
			    $user = $conn->prepare("UPDATE cliente SET telefono = :telefono, direccion = :direccion, correo = :correo, contrasena = :contrasena 
			    						WHERE id_cliente = :id_cliente");
			    $user->bindParam(':telefono', $telefono);
			    $user->bindParam(':direccion', $direccion);
			    $user->bindParam(':correo', $correo);
			    $user->bindParam(':contrasena', $contrasena);
			    $user->bindParam(':id_cliente', $id_user);

			    if($user->execute())
			    {
			    	$result = array('success' => true, 'message' => 'Perfil actualizado exitosamente.', 'user' => $id_user);
			    }
			    else
			    {
			    	$result = array('success' => false, 'message' => 'No se pudo crear el registro.');
			    }

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

