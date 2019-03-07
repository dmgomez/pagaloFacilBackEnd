<?php

header("Access-Control-Allow-Origin: *");

include_once '../database/Connection.php';


/* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
 * This code has been released into the public domain, however please      *
 * give credit to the original author where possible.                      */

function luhn_check($number) {

	// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
	$number=preg_replace('/\D/', '', $number);

	// Set the string length and parity
	$number_length=strlen($number);
	$parity=$number_length % 2;

	// Loop through each digit and do the maths
	$total=0;
	for ($i=0; $i<$number_length; $i++) {
		$digit=$number[$i];
		// Multiply alternate digits by two
		if ($i % 2 == $parity) {
			$digit*=2;
			// If the sum is two digits, add them together (in effect)
			if ($digit > 9) {
				$digit-=9;
			}
		}
		// Total up the digits
		$total+=$digit;
	}

	// If the total mod 10 equals 0, the number is valid
	return ($total % 10 == 0) ? TRUE : FALSE;

}


if (isset($_POST["accion"])) {


	session_start();
//	session_destroy();
	//$result = array('success' => true); que es esto???
	//$_SESSION['session_cliente_id_cliente'] = 43;



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
			$is_juridico  = ($_POST["is_juridico"] != "") ? ($_POST["is_juridico"]==="J" ? 1 : 0) : null;
			$prefijo_id  = ($_POST["prefijo_id"] != "") ? $_POST["prefijo_id"] : null;


			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$conn->beginTransaction();

				// prepare sql and bind parameters

				/*$cliente = $conn->prepare("INSERT INTO cliente (nombre, apellido, cedula, telefono, direccion, correo, username, contrasena, nombre_titular, numero_cuenta, ci_titular, tipo_cuenta, banco_id_banco)
                                            VALUES (:nombre, :apellido, :cedula, :telefono, :direccion, :correo, :username, :password, :titular_cuenta, :ci_cuenta, :num_cuenta, :tipo_cuenta, :id_banco)");*/
				//if($num_cuenta)

				$cliente = $conn->prepare("INSERT INTO cliente VALUES (DEFAULT, DEFAULT, DEFAULT, :telefono, :direccion, :username, :contrasena, :password, :correo, :nombre, :apellido, :cedula, :num_cuenta, :titular_cuenta, :ci_cuenta, :tipo_cuenta, DEFAULT, :id_banco, DEFAULT, DEFAULT, :is_juridico,:prefijo_id)");

				$cliente->bindParam(':nombre', $nombre);
				$cliente->bindParam(':apellido', $apellido);
				$cliente->bindParam(':cedula', $cedula);
				$cliente->bindParam(':telefono', $telefono);
				$cliente->bindParam(':direccion', $direccion);
				$cliente->bindParam(':correo', $correo);
				$cliente->bindParam(':username', $usernameP);
				$cliente->bindParam(':contrasena', sha1($passwordP));
				$cliente->bindParam(':password', sha1($passwordP));
				$cliente->bindParam(':titular_cuenta', $titular_cuenta);
				$cliente->bindParam(':ci_cuenta', $ci_cuenta);
				$cliente->bindParam(':num_cuenta', $num_cuenta);
				$cliente->bindParam(':tipo_cuenta', $tipo_cuenta);
				$cliente->bindParam(':id_banco', $id_banco);
				$cliente->bindParam(':is_juridico', $is_juridico);
				$cliente->bindParam(':prefijo_id', $prefijo_id);

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

						$cliente = $conn->prepare("SELECT * FROM cliente WHERE username = :user AND password = SHA1(:pssw) AND is_delete = :is_delete");
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
							$_SESSION['session_cliente_id_cliente'] = $result_cliente['id_cliente'];
							$_SESSION['session_cliente_correo'] = $result_cliente['correo'];

							$flag = true;
							$result = array('success' => true, 'flag' => $flag, 'user' =>$result_cliente['cedula'], 'id_cliente' => $result_cliente['id_cliente']);
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

			session_destroy();

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
			$is_delete = 0;
			$validado = 1;
			$user_mail=isset($_SESSION['session_cliente_correo']) ? $_SESSION['session_cliente_correo'] : '';
			$user_phone = isset($_SESSION['session_username']) ? $_SESSION['session_username'] : '';

			$usuario = $conn->prepare("SELECT id_cliente, cedula, correo FROM cliente WHERE 
			(cedula like '".$_POST['telefono']."%' AND cedula <>'$user_phone') 
			AND is_delete = 0 AND validado = 1 AND numero_cuenta IS NOT NULL LIMIT 10");


			$usuario->execute();



			$result = $usuario->fetchAll(PDO::FETCH_ASSOC);
			if(!count($result)){

				$usuario = $conn->prepare("SELECT id_cliente, cedula, correo FROM cliente WHERE 
			correo like '".$_POST['telefono']."%' AND correo <>'$user_mail' AND is_delete = 0 AND validado = 1 AND numero_cuenta IS NOT NULL LIMIT 10");


				$usuario->execute();
				$result = $usuario->fetchAll(PDO::FETCH_ASSOC);
				if(!count($result)){
					$result = [["telefono"=>"No hay resultados"]];
				}


			}



			break;


		case "buscarDatosUsuario":

			$result = array('success' => false, 'message' => 'No fue posible cargar los datos');

			$is_delete = 0;
			//$id_cliente_editar = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : -1;
			$id_cliente_editar = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

			if($id_cliente_editar == -1 && isset($_POST['id_cliente']))
			{
				$id_cliente_editar = $_POST['id_cliente'];
			}

			//print_r($id_cliente_editar);exit();

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$_SESSION['session_cliente_id_cliente_editar'] = $id_cliente_editar;

			$usuario = $conn->prepare("SELECT nombre, apellido, cedula, telefono, direccion, correo, username,
 												numero_cuenta,nombre_titular,ci_titular,tipo_cuenta,banco_id_banco,is_juridico,prefijo_id
										FROM cliente 
										WHERE id_cliente = :id_cliente AND is_delete = :is_delete");

			$usuario->bindParam(':id_cliente', $id_cliente_editar);
			$usuario->bindParam(':is_delete', $is_delete);
			$usuario->execute();

			/* Comprobar el número de filas que coinciden con la sentencia SELECT */

			if ($usuario->rowCount())
			{
				$result = $usuario->fetch(PDO::FETCH_ASSOC);

				$bancos = $conn->prepare("SELECT * FROM banco");
				$bancos->execute();

				$result['bancos'] = $bancos->fetchAll(PDO::FETCH_ASSOC);
				$result['success'] = true;

				/*$result = array(
					'nombre' => $result_user['nombre'],
					'apellido' => $result_user['apellido'],
					'cedula' => $result_user['cedula'],
					'telefono' => $result_user['telefono'],
					'direccion' => $result_user['direccion'],
					'correo' => $result_user['correo'],
					'username' => $result_user['username'],
					//'contrasena' => $result_user['contrasena']
				);*/

			}
			else
			{
				$result = array('success' => false, 'message' => 'Usuario no encontrado.');
			}

			break;


		case "actualizarPerfil":

			$result = array('success' => false, 'message' => 'Error. No se puede actualizar el perfil');

			$datos_perfil = $_POST["datos_perfil"];
			$telefono = $datos_perfil["telefono"];
			$direccion = $datos_perfil["direccion"];
			$correo = $datos_perfil["correo"];
			$contrasena  = $datos_perfil["contrasena"];
			$numero_cuenta = $datos_perfil["numero_cuenta"];
			$nombre_titular = $datos_perfil["nombre_titular"];
			$ci_titular = $datos_perfil["ci_titular"];
			$tipo_cuenta = $datos_perfil["tipo_cuenta"];
			$banco_id_banco = $datos_perfil["banco_id_banco"];
			$prefijo_id  = $datos_perfil["prefijo_id"];


			//$id_user = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : -1;
			$id_user = isset($_SESSION['session_cliente_id_cliente_editar']) ? $_SESSION['session_cliente_id_cliente_editar'] : -1;

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// prepare sql and bind parameters
				$user = $conn->prepare("UPDATE cliente SET telefono = :telefono, direccion = :direccion, correo = :correo, contrasena = :contrasena, numero_cuenta = :numero_cuenta,
															nombre_titular = :nombre_titular, ci_titular = :ci_titular, tipo_cuenta = :tipo_cuenta, banco_id_banco = :banco_id_banco, 
															prefijo_id = :prefijo_id
			    						WHERE id_cliente = :id_cliente");
				$user->bindParam(':telefono', $telefono);
				$user->bindParam(':direccion', $direccion);
				$user->bindParam(':correo', $correo);
				$user->bindParam(':contrasena', $contrasena);
				$user->bindParam(':id_cliente', $id_user);
				$user->bindParam(':numero_cuenta', $numero_cuenta);
				$user->bindParam(':nombre_titular', $nombre_titular);
				$user->bindParam(':ci_titular', $ci_titular);
				$user->bindParam(':tipo_cuenta', $tipo_cuenta);
				$user->bindParam(':banco_id_banco', $banco_id_banco);
				$user->bindParam(':prefijo_id', $prefijo_id);

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

		case "listarTarjetas":

			if(!isset($_SESSION)||empty($_SESSION['session_cliente_id_cliente'])){
				$result = array('success' => false, 'data' => [],'message'=>'No tienes permisos para acceder a &eacute;sta &aacute;rea');
				echo json_encode($result);
				return;
			}

			//For testing purposes
			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$res = $conn->prepare("SELECT id_tarjeta_asociada, nombre_titular, numero_tarjeta, CONCAT(mes_vencimiento,'/',ano_vencimiento) AS vencimiento, empresa_emisora_id_empresa_emisora AS tipo, 'ACTION' AS accion FROM tarjeta_asociada WHERE id_usuario_cliente = {$_SESSION['session_cliente_id_cliente']};");
			$res->execute();

			$result = array('success' => true, 'data' => $res->fetchAll(PDO::FETCH_NUM));
			break;


		case "agregarTarjeta":

			//For testing purposes
			$datos=$_POST['array_data'];
			$numero_tarjeta = $datos['numero_tarjeta'];
			$nombre_titular = $datos['nombre_titular'];
			$mes_vencimiento = $datos['mes_vencimiento'];
			$ano_vencimiento = $datos['ano_vencimiento'];
			$direccion_titular = $datos['direccion_titular'];
			$ci_titular = $datos['ci_titular'];
			$empresa_emisora_id_empresa_emisora = $datos['empresa_emisora_id_empresa_emisora'];

			foreach ($_POST['array_data'] as $key=> $val){
				if(empty($val)){
					$result = array('success' => false, 'message' => 'Datos inv&aacute;lidos');
					echo json_encode($result);
					return;
				}
			}

			if(!luhn_check($numero_tarjeta)){
				$result = array('success' => false, 'message' => 'Tarjeta inv&aacute;lida');
				echo json_encode($result);
				return;
			}

			if(strlen($nombre_titular)<2){
				$result = array('success' => false, 'message' => 'Nombre demasiado corto');
				echo json_encode($result);
				return;
			}

			if($mes_vencimiento<1||$mes_vencimiento>12){
				$result = array('success' => false, 'message' => 'Mes de vencimiento incorrecto');
				echo json_encode($result);
				return;
			}

			if($ano_vencimiento<date("Y")){
				$result = array('success' => false, 'message' => 'Tarjeta vencida');
				echo json_encode($result);
				return;
			}

			if($ano_vencimiento==date("Y") AND $mes_vencimiento < date('m')){
				$result = array('success' => false, 'message' => 'Tarjeta vencida');
				echo json_encode($result);
				return;
			}

			if(strlen($direccion_titular)<6){
				$result = array('success' => false, 'message' => 'Direcci&oacute;n demasiado corta');
				echo json_encode($result);
				return;
			}

			if(!preg_match('/^[0-9]{6,8}$/', $ci_titular)){
				$result = array('success' => false, 'message' => 'C&eacutedula inv&aacute;lida');
				echo json_encode($result);
				return;
			}

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$comp = $conn->prepare ("SELECT * FROM `tarjeta_asociada` WHERE `numero_tarjeta` = :numero_tarjeta");
			$comp->bindParam(':numero_tarjeta',$numero_tarjeta);
			$comp->execute();

			$existio = $conn->prepare ("SELECT * FROM `tarjeta_asociada` LEFT JOIN `cliente` ON `tarjeta_asociada`.id_usuario_cliente = `cliente`.`id_cliente`
										WHERE `numero_tarjeta` = :numero_tarjeta AND `cliente`.is_delete = 1 ");
			$existio->bindParam(':numero_tarjeta',$numero_tarjeta);
			$existio->execute();

			if ((!($comp->rowCount()))||($existio->rowCount())){

				$res = $conn->prepare("INSERT INTO `tarjeta_asociada`(`numero_tarjeta`, `nombre_titular`, `mes_vencimiento`, `ano_vencimiento`, `direccion_titular`, `id_usuario_cliente`, `ci_titular`, `empresa_emisora_id_empresa_emisora`) 
										VALUES (:numero_tarjeta ,:nombre_titular, :mes_vencimiento, :ano_vencimiento,:direccion_titular,:id_usuario_cliente,:ci_titular, :empresa_emisora_id_empresa_emisora)");
				$res->bindParam(':numero_tarjeta',$numero_tarjeta);
				$res->bindParam(':nombre_titular', $nombre_titular);
				$res->bindParam(':mes_vencimiento', $mes_vencimiento);
				$res->bindParam(':ano_vencimiento', $ano_vencimiento);
				$res->bindParam(':direccion_titular', $direccion_titular);
				$res->bindParam(':id_usuario_cliente',$_SESSION['session_cliente_id_cliente']);
				$res->bindParam(':ci_titular', $ci_titular);
				$res->bindParam(':empresa_emisora_id_empresa_emisora', $empresa_emisora_id_empresa_emisora);
				$res->execute();



				$result = array('success' => true);
			}
			else{
				$result = array('success' => false, 'message' => 'La tarjeta se encuentra registrada en el sistema');

			}
			break;


		case "eliminarTarjeta":


			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$comp = $conn->prepare ("SELECT * FROM `tarjeta_asociada` WHERE `id_tarjeta_asociada` = :id_tarjeta_asociada AND id_usuario_cliente = :id_usuario_cliente");
			$comp->bindParam(':id_tarjeta_asociada',$_POST['id_tarjeta_asociada']);
			$comp->bindParam(':id_usuario_cliente',$_SESSION['session_cliente_id_cliente']);
			$comp->execute();

			if($comp->rowCount()){
				$res = $conn->prepare("DELETE FROM `tarjeta_asociada` WHERE `id_tarjeta_asociada` = :id_tarjeta_asociada");
				$res->bindParam(':id_tarjeta_asociada',$_POST['id_tarjeta_asociada']);
				$res->execute();

				$result = array('success' => true);
			}
			else{
				$result = array('success' => false, 'message' => 'La tarjeta no te pertenece');
			}
			break;

		case "obtenerTarjeta":

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$res = $conn->prepare("SELECT * FROM `tarjeta_asociada` WHERE `id_tarjeta_asociada` = :id_tarjeta_asociada");
			$res->bindParam(':id_tarjeta_asociada',$_POST['id_tarjeta_asociada']);
			$res->execute();

			if($res->rowCount()){
				$result = array('success' => true, 'data' => $res->fetch(PDO::FETCH_ASSOC));
			}
			else{
				$result = array('success' => false, 'message' => 'No existe la tarjeta');
			}
			break;

		case "modificarTarjeta":
			//For testing purposes
			$datos=$_POST['array_data'];
			$id_tarjeta_asociada = $datos['id_tarjeta_asociada'];
			$numero_tarjeta = $datos['numero_tarjeta'];
			$nombre_titular = $datos['nombre_titular'];
			$mes_vencimiento = $datos['mes_vencimiento'];
			$ano_vencimiento = $datos['ano_vencimiento'];
			$direccion_titular = $datos['direccion_titular'];
			$ci_titular = $datos['ci_titular'];
			$empresa_emisora_id_empresa_emisora = $datos['empresa_emisora_id_empresa_emisora'];

			foreach ($_POST['array_data'] as $key=> $val){
				if(empty($val)){
					$result = array('success' => false, 'message' => 'Datos invalidos',$key=>$val);
					echo json_encode($result);
					return;
				}
			}

			if(!luhn_check($numero_tarjeta)){
				$result = array('success' => false, 'message' => 'Tarjeta inv&aacute;lida');
				echo json_encode($result);
				return;
			}

			if(strlen($nombre_titular)<2){
				$result = array('success' => false, 'message' => 'Nombre demasiado corto');
				echo json_encode($result);
				return;
			}

			if($mes_vencimiento<1||$mes_vencimiento>12){
				$result = array('success' => false, 'message' => 'Mes de vencimiento incorrecto');
				echo json_encode($result);
				return;
			}

			if($ano_vencimiento<date("Y")){
				$result = array('success' => false, 'message' => 'Tarjeta vencida');
				echo json_encode($result);
				return;
			}

			if($ano_vencimiento==date("Y") AND $mes_vencimiento < date('m')){
				$result = array('success' => false, 'message' => 'Tarjeta vencida');
				echo json_encode($result);
				return;
			}

			if(strlen($direccion_titular)<6){
				$result = array('success' => false, 'message' => 'Direcci&oacute;n demasiado corta');
				echo json_encode($result);
				return;
			}

			if(!preg_match('/^[0-9]{6,8}$/', $ci_titular)){
				$result = array('success' => false, 'message' => 'C&eacutedula inv&aacute;lida');
				echo json_encode($result);
				return;
			}

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$comp = $conn->prepare ("SELECT * FROM `tarjeta_asociada` WHERE `id_tarjeta_asociada` = :id_tarjeta_asociada AND id_usuario_cliente = :id_usuario_cliente");
			$comp->bindParam(':id_tarjeta_asociada',$id_tarjeta_asociada);
			$comp->bindParam(':id_usuario_cliente',$_SESSION['session_cliente_id_cliente']);
			$comp->execute();


			$exist = $conn->prepare ("SELECT * FROM `tarjeta_asociada` WHERE `numero_tarjeta` = :numero_tarjeta AND `id_tarjeta_asociada` = :id_tarjeta_asociada AND id_usuario_cliente = :id_usuario_cliente");
			$exist->bindParam(':numero_tarjeta',$numero_tarjeta);
			$exist->bindParam(':id_tarjeta_asociada',$id_tarjeta_asociada);
			$exist->bindParam(':id_usuario_cliente',$_SESSION['session_cliente_id_cliente']);
			$exist->execute();

			$exista = $conn->prepare ("SELECT * FROM `tarjeta_asociada` WHERE `numero_tarjeta` = :numero_tarjeta");
			$exista->bindParam(':numero_tarjeta',$numero_tarjeta);
			$exista->execute();

			$existio = $conn->prepare ("SELECT * FROM `tarjeta_asociada` LEFT JOIN `cliente` ON `tarjeta_asociada`.id_usuario_cliente = `cliente`.`id_cliente`
										WHERE `numero_tarjeta` = :numero_tarjeta AND `cliente`.is_delete = 1 ");
			$existio->bindParam(':numero_tarjeta',$numero_tarjeta);
			$existio->execute();


			if(($comp->rowCount())&&(($exist->rowCount())||(!$exista->rowCount())||($existio->rowCount()))){

				$res = $conn->prepare("UPDATE `tarjeta_asociada` SET `numero_tarjeta`= :numero_tarjeta,`nombre_titular`= :nombre_titular,`mes_vencimiento`= :mes_vencimiento,`ano_vencimiento`= :ano_vencimiento,
											`direccion_titular`= :direccion_titular,`ci_titular`= :ci_titular,`empresa_emisora_id_empresa_emisora`= :empresa_emisora_id_empresa_emisora 
											WHERE `id_tarjeta_asociada`= :id_tarjeta_asociada");
				$res->bindParam(':id_tarjeta_asociada',$id_tarjeta_asociada);
				$res->bindParam(':numero_tarjeta',$numero_tarjeta);
				$res->bindParam(':nombre_titular', $nombre_titular);
				$res->bindParam(':mes_vencimiento', $mes_vencimiento);
				$res->bindParam(':ano_vencimiento', $ano_vencimiento);
				$res->bindParam(':direccion_titular', $direccion_titular);
				$res->bindParam(':ci_titular', $ci_titular);
				$res->bindParam(':empresa_emisora_id_empresa_emisora', $empresa_emisora_id_empresa_emisora);
				$res->execute();

				$result = array('success' => true);
			}
			else{
				$result = array('success' => false, 'message' => 'La Tarjeta No Te Pertenece');

			}

			break;


		case "cargarUltimosCinco":

			$result = array('success' => false);

			$id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

			if($id_cliente == -1 && isset($_POST['id_cliente']))
			{
				$id_cliente = $_POST['id_cliente'];
			}

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$is_delete = 0;
				$cliente = $conn->prepare("SELECT id_cliente, telefono, username, correo, nombre, apellido, cedula
				 							FROM cliente 
				 							WHERE id_cliente IN (SELECT receptor_id FROM transaccion WHERE emisor_id = :user) 
				 									and is_delete = :is_delete");

				$cliente->bindParam(':user', $id_cliente);
				$cliente->bindParam(':is_delete', $is_delete);
				$cliente->execute();

				$result_cliente = $cliente->fetchAll(PDO::FETCH_ASSOC);

				if(count($result_cliente) > 0)
				{
					$result = '<ul class="collection">';
					foreach ($result_cliente as $key => $value) {
					   
					   	$result .= '		   	
					    <li class="collection-item avatar">
					      <i class="material-icons circle">person</i>
					      <span class="title">'.$value['username'].'</span>
					      '.$value['correo'].'
					      
					      <a href="#!" id="'.$value['cedula'].'" class="secondary-content ultimos-cinco"><i class="material-icons">add_box</i></a>
					    </li>';
					}

					$result .= '</ul>';

					$result = array('success' => true, /*'flag' => $flag,*/ 'clientes' =>$result);
				}


				$conn = null;

			}

			catch(PDOException $e)

			{

				$result = array('success' => false, 'message' => "Error: " . $e->getMessage());

			}

			break;


		case "cargarFavoritos":

			$result = array('success' => false);

			$id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

			if($id_cliente == -1 && isset($_POST['id_cliente']))
			{
				$id_cliente = $_POST['id_cliente'];
			}

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$is_delete = 0;
				$cliente = $conn->prepare("SELECT id_cliente, telefono, username, correo, nombre, apellido, cedula
				 							FROM cliente 
				 							WHERE id_cliente IN (SELECT id_cliente_favorito FROM favorito WHERE id_cliente = :user) 
				 									and is_delete = :is_delete");

				$cliente->bindParam(':user', $id_cliente);
				$cliente->bindParam(':is_delete', $is_delete);
				$cliente->execute();

				$result_cliente = $cliente->fetchAll(PDO::FETCH_ASSOC);

				if(count($result_cliente) > 0)
				{
					$result = '<ul class="collection">';
					foreach ($result_cliente as $key => $value) {
					   
					   	$result .= '		   	
					    <li class="collection-item avatar">
					      <i class="material-icons circle">person</i>
					      <span class="title">'.$value['username'].'</span>
					      '.$value['correo'].'
					      
					      <a href="#!" id="'.$value['cedula'].'" class="secondary-content cliente-favorito"><i class="material-icons">add_box</i></a>
					    </li>';
					}

					$result .= '</ul>';

					$result = array('success' => true, /*'flag' => $flag,*/ 'clientes' =>$result);
				}


				$conn = null;

			}

			catch(PDOException $e)

			{

				$result = array('success' => false, 'message' => "Error: " . $e->getMessage());

			}

			break;


		case "agregarFavorito":

			$result = array('success' => false, 'message' => 'Error. No se pudo agregar a favoritos');

			$id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

			if($id_cliente == -1 && isset($_POST['id_cliente']))
			{
				$id_cliente = $_POST['id_cliente'];
			}

			$id_favorito = $_POST['id_favorito'];

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$cliente_favorito = $conn->prepare("SELECT id_cliente FROM cliente WHERE cedula = :favorito");
				$cliente_favorito->bindParam(':favorito',$_POST['favorito']);
				$cliente_favorito->execute();

				$result_favorito = $cliente_favorito->fetch(PDO::FETCH_ASSOC);
				

				if($result_favorito && count($result_favorito) > 0)
				{
					$favorito_existente = $conn->prepare ("SELECT * FROM favorito WHERE id_cliente = :id_cliente AND id_cliente_favorito = :id_cliente_favorito");
					$favorito_existente->bindParam(':id_cliente', $id_cliente);
					$favorito_existente->bindParam(':id_cliente_favorito', $result_favorito['id_cliente']);
					$favorito_existente->execute();
					
					if (!($favorito_existente->rowCount()))
					{
						$favorito = $conn->prepare("INSERT INTO favorito VALUES (DEFAULT, :id_cliente, :id_cliente_favorito)");

						$favorito->bindParam(':id_cliente', $id_cliente);
						$favorito->bindParam(':id_cliente_favorito', $result_favorito['id_cliente']);
						
						if($favorito->execute())
						{
							$result = array('success' => true, 'message' => 'Favorito agregado satisfactoriamente');
						}
					}
					else
					{
						$result = array('success' => false, 'message' => 'Favorito ya existente');
					}
				}
				else
				{
					$result = array('success' => false, 'message' => 'La cédula ingresada no corresponde a ningún usuario');
				}

				$conn = null;

			}

			catch(PDOException $e)

			{

				$result = array('success' => false, 'message' => "Error: " . $e->getMessage());

			}

			break;


		case "enviarReclamo":

			$result = array('success' => false, 'message' => 'Error. No se puede enviar el reclamo');

			$datos_reclamo = $_POST["datos_reclamo"];
			$nombre = $datos_reclamo["nombre"];
			$apellido = $datos_reclamo["apellido"];
			$cedula = $datos_reclamo["cedula"];
			$telefono = $datos_reclamo["telefono"];
			$correo = $datos_reclamo["correo"];
			$motivo_reclamo = $datos_reclamo["motivo_reclamo"];
			$descripcion_reclamo = $datos_reclamo["descripcion_reclamo"];


			//$id_user = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : -1;
			$id_user = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : null;

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$ultimo_reclamo = $conn->prepare("SELECT id_reclamo FROM reclamo ORDER BY id_reclamo DESC LIMIT 1");
				$ultimo_reclamo->execute();

				$result_ultimo_reclamo = $ultimo_reclamo->fetch(PDO::FETCH_ASSOC);

				/* Comprobar el número de filas que coinciden con la sentencia SELECT */
				if ($result_ultimo_reclamo > 0)
				{
					$id_ultimo_reclamo = $result_ultimo_reclamo['id_reclamo'] + 1;
				}
				else
				{
					$id_ultimo_reclamo = 1;
				}

				$numero_reclamo = 'R-'.date('dmY').$id_ultimo_reclamo;


				$reclamo = $conn->prepare("INSERT INTO reclamo VALUES (DEFAULT, :numero_reclamo, :motivo_reclamo, :descripcion_reclamo, DEFAULT, DEFAULT, :id_cliente, :nombre_cliente, :apellido_cliente, :cedula_cliente, :telefono_cliente, :correo_cliente, DEFAULT, DEFAULT)");

				$reclamo->bindParam(':numero_reclamo', $numero_reclamo);
				$reclamo->bindParam(':motivo_reclamo', $motivo_reclamo);
				$reclamo->bindParam(':descripcion_reclamo', $descripcion_reclamo);
				$reclamo->bindParam(':id_cliente', $id_user);
				$reclamo->bindParam(':nombre_cliente', $nombre);
				$reclamo->bindParam(':apellido_cliente', $apellido);
				$reclamo->bindParam(':cedula_cliente', $cedula);
				$reclamo->bindParam(':telefono_cliente', $telefono);
				$reclamo->bindParam(':correo_cliente', $correo);
				
				if($reclamo->execute())
				{
					$id_reclamo = $conn->lastInsertId();

					if($id_ultimo_reclamo != $id_reclamo)
					{
						$numero_reclamo = 'R-'.date('dmY').$id_reclamo;

						$reclamo_update = $conn->prepare("UPDATE reclamo SET numero_reclamo = :numero_reclamo WHERE id_reclamo = :id_reclamo");
						$reclamo_update->bindParam(':numero_reclamo', $numero_reclamo);
						$reclamo_update->bindParam(':id_reclamo', $id_reclamo);

						if($reclamo_update->execute())
						{
							$result = array('success' => true, 'message' => 'Reclamo enviado exitosamente.');
						}
						else
						{
							$result = array('success' => false, 'message' => 'No se pudo actualizar el numero de reclamo.');
						}
					}
					else
					{
						$result = array('success' => true, 'message' => 'Reclamo enviado exitosamente.');
					}

				}
				else
				{
					$result = array('success' => false, 'message' => 'No se pudo enviar el reclamo.');
				}

				$conn = null;

			}
			catch(PDOException $e)
			{
				$result = array('success' => false, 'message' => "Error: " . $e->getMessage());
			}

			break;

		case "listarTransacciones":

			if(!isset($_SESSION)||empty($_SESSION['session_cliente_id_cliente'])){
				$result = array('success' => false, 'data' => [],'message'=>'No tienes permisos para acceder a &eacute;sta &aacute;rea');
				echo json_encode($result);
				return;
			}

            try {


                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

                // set the PDO error mode to exception

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $listado_trans = $conn->prepare("SELECT  CONCAT(rc.nombre,' ',rc.apellido) as receptor,
													tr.monto_transaccion as cantidad, rc.numero_cuenta as numero_de_cuenta, tr.fecha as fecha
													FROM transaccion AS tr
													LEFT JOIN cliente rc ON tr.receptor_id = rc.id_cliente
														WHERE
														emisor_id=".$_SESSION['session_cliente_id_cliente']." ORDER BY tr.fecha  DESC");

                $listado_trans->execute(); 
                


                $result_trans = $listado_trans->fetchAll(PDO::FETCH_NUM);

 

                $result = array('success' => true, 'data' => $result_trans);
               // echo($result);


                $conn = null;

            } catch (PDOException $e) {

                $result = array('success' => false, 'message' => "Error: " . $e->getMessage());

            }
		break;


		case "buscarUsuarioColectivo":

			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

			// set the PDO error mode to exception

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$_POST['telefono'] = isset($_POST['telefono']) ? $_POST['telefono']."%" : '';
			$participantes = isset($_POST['participantes']) ? $_POST['participantes'] : '-1';
			$is_delete = 0;
			//session_cliente_correo
            $user_mail=isset($_SESSION['session_cliente_correo']) ? $_SESSION['session_cliente_correo'] : '';
			$user_phone = isset($_SESSION['session_username']) ? $_SESSION['session_username'] : '';

			$usuario = $conn->prepare("SELECT DISTINCT c.id_cliente, c.cedula 
										FROM cliente AS c INNER JOIN tarjeta_asociada AS t ON c.id_cliente = t.id_usuario_cliente
										WHERE c.cedula like :telefono AND c.cedula <> '$user_phone' AND 
												c.id_cliente NOT IN ($participantes) AND c.is_delete = :is_delete  LIMIT 10");



			$usuario->bindParam(':telefono',$_POST['telefono']);
			$usuario->bindParam(':is_delete',$is_delete);

			$usuario->execute();

			$result = $usuario->fetchAll(PDO::FETCH_ASSOC);



			if(!count($result)){

				$result = [["telefono"=>"No hay resultados"]];

			}



			break;



		case "agregarParticipantePagoColectivo":

			$result = array('success' => false);

			$id_participante = $_POST['id_participante'];

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$is_delete = 0;
				$cliente = $conn->prepare("SELECT id_cliente, telefono, username, correo, nombre, apellido, cedula
				 							FROM cliente 
				 							WHERE id_cliente = :user
				 									and is_delete = :is_delete");

				$cliente->bindParam(':user', $id_participante);
				$cliente->bindParam(':is_delete', $is_delete);
				$cliente->execute();

				$result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);


				if($result_cliente > 0)
				{
					$participante = '
					<div class="col s12 m12 l12" id="p_'.$id_participante.'">
				        <div class="card-panel grey lighten-5 z-depth-1" style="padding-top: 3px; padding-bottom: 0px;">
					         <div class="row valign-wrapper">
					            <div class="col s2">
					             	<img src="images/fondo1.png" alt="" class="circle responsive-img"> <!-- notice the "circle" class -->
					            </div>
					            <div class="col s10">
					              	<span class="black-text">
					              		<b>'.$result_cliente['username'].'</b><br>
					                	'.$result_cliente['correo'].'
					                	<a href="#!" id="eliminar_p_'.$id_participante.'" class="secondary-content eliminar-participante"><i class="material-icons">delete</i></a>
										
						                        <input id="monto_p_'.$id_participante.'" placeholder="Monto" type="text" class="validate monto-participante" required aria-required="true" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)">
						                       
					              	</span>
					            </div>
					        </div>
				        </div>
				     </div>';

					/*$result = '<ul class="collection">';
					foreach ($result_cliente as $key => $value) {
					   
					   	$result .= '		   	
					    <li class="collection-item avatar">
					      <i class="material-icons circle">person</i>
					      <span class="title">'.$value['username'].'</span>
					      '.$value['correo'].'
					      
					      <a href="#!" id="'.$value['cedula'].'" class="secondary-content cliente-favorito"><i class="material-icons">add_box</i></a>
					    </li>';
					}

					$result .= '</ul>';*/

					$result = array('success' => true, /*'flag' => $flag,*/ 'participante' =>$participante);
				}


				$conn = null;

			}

			catch(PDOException $e)

			{

				$result = array('success' => false, 'message' => "Error: " . $e->getMessage());

			}

			break;


		case "cargarTarjeta":

			$result = array('success' => false, 'message' => 'No posee tarjeta asociada');

			$id_cliente = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : -1;

            if($id_cliente == -1 && isset($_POST['id_cliente']))
            {
                $id_cliente = $_POST['id_cliente'];
            }

			try {

				$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$tdc = $conn->prepare("SELECT t.id_tarjeta_asociada, t.numero_tarjeta, e.id_empresa_emisora, e.nombre_empresa 
													FROM tarjeta_asociada AS t 
														INNER JOIN empresa_emisora AS e ON t.empresa_emisora_id_empresa_emisora = e.id_empresa_emisora
													WHERE t.id_usuario_cliente = :id_cliente");
				
				$tdc->bindParam(':id_cliente', $id_cliente);
				$tdc->execute();

				$result_tdc = $tdc->fetchAll(PDO::FETCH_ASSOC);

				$result = array('success' => true, 'result_tdc' => $result_tdc);

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

