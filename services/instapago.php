<?php
header("Access-Control-Allow-Origin: *");
include_once '../database/Connection.php';
session_start();
extract($_POST['payment']);


if(!isset($_POST['payment'])||!isset($_POST['user'])){
    exit(-1);
}

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

//se Verifica de que sea un numero de tarjeta de credito valido

if(!luhn_check($cardNumber)){
    echo json_encode([
    	'success'=>'false',
    	'message'=>'Tarjeta Inválida'
    					]); 
    exit;
}



$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$is_delete = 0;

$cliente = $conn->prepare("SELECT id_cliente FROM cliente WHERE ci_titular = :ci_titular AND is_delete = :is_delete");
$cliente->bindParam(':ci_titular', $_POST['user']);
$cliente->bindParam(':is_delete', $is_delete);
$cliente->execute();

$result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);

if($cliente->rowCount()===0)
{
	echo json_encode([
		'success'=>'false',
		'message'=>'Receptor Inválido'
		]);
    exit(-1);
}

$receptor_id = $result_cliente['id_cliente'];


$ch = curl_init();
$url = 'https://api.instapago.com/payment';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "KeyId=D414AEA3-DBF7-443E-9E0F-DC669D99D544"
    . "&PublicKeyId=e475d9510184b4f73e3b7a2e508e759a"
    . "&Amount=$amount"
    . "&Description=$description"
    . "&CardHolder=$cardHolder"
    . "&CardHolderID=$cardHolderID"
    . "&CardNumber=$cardNumber"
    . "&CVC=$cvc"
    . "&ExpirationDate=$expirationDateMonth/$expirationDateYear"
    . "&StatusId=1"

);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo 'Ocurrio un error inesperado: '.curl_error($ch);
    die;
}
else{
    //echo "ENTRAAAAA";
    //correo
    $correo="NADA";
    $user_mail=$_POST['user'];
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "CONSULTA SELECT correo FROM cliente WHERE cedula = ".$user_mail;
    $usuario = $conn->prepare("SELECT * FROM cliente WHERE cedula = '".$user_mail."'");
    // $usuario = $conn->prepare("SELECT * FROM cliente");

    //$usuario->bindParam(':cedula',$user_mail);

    $usuario->execute();

    $result = $usuario->fetch(PDO::FETCH_ASSOC);
    //echo $result['cedula'];
    if(!count($result)){

        $correo = false;
        //echo "CORREO: NO";
    }
    else{
        $correo = $result['correo'];
        //echo  "CORREO: ".$result['correo'];
        //print_r($result);
    }

    $mail = "Has recibido un pago a través de nuestra plataforma :
                        Monto:$amount bsf
                        Esta solicitud será procesada por nosotros y se acreditará en su cuenta asociada";

    //Titulo
    $titulo = "Pago recibido";
    //cabecera
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    //dirección del remitente
    $headers .= "From: PagaloFacil < support@pagalofacil.com >\r\n";
    //Enviamos el mensaje a tu_dirección_email
    $bool = mail($correo,$titulo,$mail,$headers);


}

$json_response = json_decode($answer);

if(is_null($json_response ))
{
    echo json_decode(['success'=>false]);
    return;
}


/*
 * 00,10,11 son valores de transaccion exitosa segun instapago
 * El valor por defecto de estatus_transaccion es '1' = Pendiente
 * el valor '3' corresponde a Rechazada
 * **/
$estatus_transaccion = in_array($json_response->responsecode,["00","10","11"]) ? '2' : '3';
$emisor_id = isset($_SESSION['session_cliente_id_cliente']) ? $_SESSION['session_cliente_id_cliente'] : 'NULL';


// Se verifica a que compañia de tarjeta de credito pertenece
    
    $cardtype = array(
        "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
        "mastercard" => "/^5[1-5][0-9]{14}$/",
        "amex"       => "/^3[47][0-9]{13}$/",
    );

    if (preg_match($cardtype['visa'],$cardNumber))
    	{
		$empresa_tarjeta = 1;
    	}
    	else if (preg_match($cardtype['mastercard'],$cardNumber))
    	{
        $empresa_tarjeta = 2;
    	}
    	else if (preg_match($cardtype['amex'],$cardNumber))
    	{
        $empresa_tarjeta = 3;
   	 	}
        else
    	{
        $empresa_tarjeta = 4;
    	} 
    	

//Continuar insertando la transaccion

$transaccion = $conn->prepare("INSERT INTO transaccion VALUES(DEFAULT,DEFAULT ,:receptor_id,:emisor_id,:cardNumber,:cardHolder,:cardHolderID,:empresa_tarjeta
	,DEFAULT,DEFAULT, :description,:amount,DEFAULT,:estatus_transaccion)");

$transaccion->bindParam(':emisor_id', $emisor_id);
$transaccion->bindParam(':receptor_id', $receptor_id);
$transaccion->bindParam(':estatus_transaccion', $estatus_transaccion);
$transaccion->bindParam(':amount', $amount);
$transaccion->bindParam(':description', $description);
$transaccion->bindParam(':cardNumber', $cardNumber);
$transaccion->bindParam(':cardHolder', $cardHolder);
$transaccion->bindParam(':cardHolderID', $cardHolderID);
$transaccion->bindParam(':estatus_transaccion', $estatus_transaccion);
$transaccion->bindParam(':empresa_tarjeta', $empresa_tarjeta);


if($transaccion->execute())
{
    echo $answer;
    exit;
}
else
{
    echo json_encode(['success'=>'false']);
}