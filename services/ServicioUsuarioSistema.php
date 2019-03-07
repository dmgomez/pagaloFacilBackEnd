<?php

header("Access-Control-Allow-Origin: *");

include_once '../database/Connection.php';



if (isset($_POST["accion"])) {

	

	$result = array('success' => false);



    switch ($_POST["accion"]) {


        case "cargarComboRol":

        	try {

			    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			    // set the PDO error mode to exception
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    $rol = $conn->prepare("SELECT id_rol, rol FROM rol"); 
			    $rol->execute();

			    $result_rol = $rol->fetchAll(PDO::FETCH_ASSOC); 

			    $result = array('success' => true, 'result_rol' => $result_rol);

			    $conn = null; 

			}
			catch(PDOException $e)
			{
			   $result = array('success' => false, 'message' => "Error: " . $e->getMessage());
			}	
            

            break;


        case "registrarUsuarioSistema":
        	
        	$nombre = $_POST["nombre"];

			$apellido = $_POST["apellido"];

			$cedula = $_POST["cedula"];

			$correo = $_POST["correo"];

			$rol = intval($_POST["rol"]);

			$usernameP  = $_POST["username"];

			$passwordP  = $_POST["password"];

        	try {



			    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			    // set the PDO error mode to exception

			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


			    // prepare sql and bind parameters

			    $user = $conn->prepare("INSERT INTO usuario_sistema VALUES (DEFAULT, :username, :contrasena, :correo,:rol, :nombre, :apellido, :cedula, DEFAULT, DEFAULT)");

			    $user->bindParam(':nombre', $nombre);
			    $user->bindParam(':apellido', $apellido);
			    $user->bindParam(':cedula', $cedula);
			    $user->bindParam(':correo', $correo);
			    $user->bindParam(':rol', $rol);
			    $user->bindParam(':username', $usernameP);
			    $user->bindParam(':contrasena', $passwordP);

			  
			    if($user->execute())

			    {
			    	
			    	$result = array('success' => true);

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



        case "iniciarSesionUsuarioSistema":

			session_start();

			$result = array('success' => false, "message" => "debe llenar los datos de acceso");
			if (isset($_POST["recaptcha"]))
				$captcha = $_POST["recaptcha"];
			if (!$captcha)
				$result = array('success' => false, 'message' => "Error: fallo de verificación");
			// handling the captcha and checking if it's ok
			$secret = "6Ld-xxAUAAAAAH8-TBcdD2-h_E17_AhqgUfkvWDI";
			$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);


			if ($response["success"] != false) {
				if(!empty($_POST['user']) && !empty($_POST['pssw'])) {

					$usuario=$_POST['user'];

					$pssw=$_POST['pssw'];



					try {

						$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

						// set the PDO error mode to exception
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						$is_delete = 0;

						$user = $conn->prepare("SELECT * FROM usuario_sistema WHERE username = :user AND contrasena = :pssw /*AND is_delete = :is_delete*/");
						$user->bindParam(':user', $usuario);
						$user->bindParam(':pssw', $pssw);
						//$user->bindParam(':is_delete', $is_delete);

						$user->execute();

						$result_user = $user->fetch(PDO::FETCH_ASSOC);


						//$flag = false;

						/* Comprobar el número de filas que coinciden con la sentencia SELECT */

						if ($result_user > 0)

						{

							//$_SESSION['session_user']=$result_user['id_usuario'];
							//Para efectos del nombre de perfil y para identificar al usuario
							/*$_SESSION['usuario_sistema_id_usuario'] = $result_user['id_usuario'];
							$_SESSION['usuario_sistema_nombre'] = $result_user['nombre'];
							$_SESSION['usuario_sistema_rol']  = $result_user['rol'];*/
							//$flag = true;

							if($result_user['is_delete'] == 0)
							{
								$_SESSION['usuario_sistema_id_usuario'] = $result_user['id_usuario'];
								$_SESSION['usuario_sistema_nombre'] = $result_user['nombre'];
								$_SESSION['usuario_sistema_rol']  = $result_user['rol'];

								$result = array('success' => true, /*'flag' => $flag,*/ 'user' => $_SESSION['usuario_sistema_id_usuario']);	
							}
							else{
								$result = array('success' => false, /*'flag' => $flag,*/ 'user' => null, "message" => "Usuario bloqueado");
							}							

						}

						else{
							$result = array('success' => false, /*'flag' => $flag,*/ 'user' => null, "message" => "Usuario o contraseña incorrecto");
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



		case "cerrarSesion":

			// remove all session variables
			session_unset();

			// destroy the session
			session_destroy();

			//$_SESSION = array();



			if(empty($_SESSION['session_user'])) {

				$result = array('success' => true, 'flag' => true);



			}

			break;


		case "mostrarForm":

			session_start();
			
			$result = array('success' => false, 'message' => 'No es admin');

			$form = $_POST['form'];
			
			$id_user = isset($_SESSION['usuario_sistema_id_usuario']) ? $_SESSION['usuario_sistema_id_usuario'] : -1;

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$rol_usuario = $conn->prepare("SELECT rol FROM usuario_sistema WHERE id_usuario = :id_user");
			$rol_usuario->bindParam(':id_user', $id_user);
			$rol_usuario->execute();

			$result_rol = $rol_usuario->fetch(PDO::FETCH_ASSOC);

	        /* Comprobar el número de filas que coinciden con la sentencia SELECT */

	        if ($result_rol > 0)
	        {
	        	$id_rol = $result_rol['rol'];
	        }
	        else
	        {
	        	$id_rol = -1;
	        }


			if( ($form == 'R' && $id_rol == 1) || ($form == 'E' && ($id_rol == 1 || $id_rol == 2)) )
			{
				$result = array('success' => true);
			}
			


			break;



		case "buscarUsuario":

			session_start();
			
			$result = array('success' => false, 'message' => 'No fue posible cargar los datos');

			$is_delete = 0;
			$id_user = isset($_SESSION['usuario_sistema_id_usuario']) ? $_SESSION['usuario_sistema_id_usuario'] : -1;

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$rol_usuario = $conn->prepare("SELECT rol FROM usuario_sistema WHERE id_usuario = :id_user");
			$rol_usuario->bindParam(':id_user', $id_user);
			$rol_usuario->execute();

			$result_rol = $rol_usuario->fetch(PDO::FETCH_ASSOC);

	        /* Comprobar el número de filas que coinciden con la sentencia SELECT */

	        if ($result_rol > 0)
	        {
	        	$id_rol = $result_rol['rol'];
	        }
	        else
	        {
	        	$id_rol = -1;
	        }


			if($id_rol == 1)
			{
				$id_usuario_editar = $_POST['id_usuario'];
			}
			else
			{
				$id_usuario_editar = $id_user;
			}

			$_SESSION['usuario_sistema_id_usuario_editar'] = $id_usuario_editar;

			$usuario = $conn->prepare("SELECT us.nombre, us.apellido, us.cedula, us.rol AS id_rol, us.correo, us.username, r.rol 
										FROM usuario_sistema AS us INNER JOIN rol AS r ON us.rol = r.id_rol
										WHERE id_usuario = :id_user AND is_delete = :is_delete");
			$usuario->bindParam(':id_user', $id_usuario_editar);
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
	            	'rol' => $result_user['rol'],
	            	'correo' => $result_user['correo'],
	            	'username' => $result_user['username'],
	            	//'contrasena' => $result_user['contrasena']
	            );

	        }
	        else
	        {
	        	$result = array('success' => false, 'message' => 'Usuario no encontrado.'.$id_user);
	        }

			break;


		case "actualizarPerfil":

			session_start();
			
			$result = array('success' => false, 'message' => 'Error. No se puede actualizar el perfil');

			$correo = $_POST["correo"];
			$contrasena  = $_POST["contrasena"];
			$id_user = isset($_SESSION['usuario_sistema_id_usuario_editar']) ? $_SESSION['usuario_sistema_id_usuario_editar'] : -1;

        	try {

			    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			    // set the PDO error mode to exception
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    // prepare sql and bind parameters
			    $user = $conn->prepare("UPDATE usuario_sistema SET correo = :correo, contrasena = :contrasena WHERE id_usuario = :id_usuario");
			    $user->bindParam(':correo', $correo);
			    $user->bindParam(':contrasena', $contrasena);
			    $user->bindParam(':id_usuario', $id_user);

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

