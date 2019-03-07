<?php
header("Access-Control-Allow-Origin: *");
include_once '../database/Connection.php';
session_start();
//extract($_POST['payment']);


if(!isset($_POST['payment'])){
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

$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$success = true;
$receptor = -1;
$error_tarjeta = false;
$error_inesperado = false;

foreach ($_POST['payment'] as $key => $value) {
    
    if($value['id_estatus_transaccion'] != 2)
    {
        $is_delete = 0;

        $transaccion = $conn->prepare("SELECT t.id_transaccion, t.id_factura, t.emisor_id, t.receptor_id, t.numero_tarjeta, t.nombre_titular, t.ci_titular,
                                                CONCAT(t.mes_vencimiento, '/', t.ano_vencimiento) AS expiracion, t.mes_vencimiento, t.ano_vencimiento, 
                                                t.asunto_transaccion, t.monto_transaccion 
                                        FROM transaccion t
                                        WHERE t.id_transaccion = :id_transaccion");
        $transaccion->bindParam(':id_transaccion', $value['id_transaccion']);
        $transaccion->execute();
                                 
        $result_transaccion = $transaccion->fetch(PDO::FETCH_ASSOC);

        $receptor = $result_transaccion['receptor_id'];

        $tabla_relacionada = "transaccion";

        $tmp = $conn->prepare("SELECT codigo
                                FROM configuracion_tmp
                                WHERE tabla_relacionada = :tabla_relacionada AND id_tabla_relacionada = :id_tabla_relacionada");
        $tmp->bindParam(':tabla_relacionada', $tabla_relacionada);
        $tmp->bindParam(':id_tabla_relacionada', $value['id_transaccion']);
        $tmp->execute();
                                 
        $result_tmp = $tmp->fetch(PDO::FETCH_ASSOC);

        //se Verifica de que sea un numero de tarjeta de credito valido
        if(!luhn_check($result_transaccion['numero_tarjeta'])){
            $error_tarjeta = true;
            $tarjeta_invalida[] = $result_transaccion['nombre_titular'];
            $success = false;
            break;
        }

        $monto = $result_transaccion['monto_transaccion'];
        $asunto = $result_transaccion['asunto_transaccion'];
        $titular = $result_transaccion['nombre_titular'];
        $ci = $result_transaccion['ci_titular'];
        $tarjeta = $result_transaccion['numero_tarjeta'];
        $cvc = $result_tmp['codigo'];
        $mes = $result_transaccion['mes_vencimiento'];
        $ano = $result_transaccion['ano_vencimiento'];
            

        $ch = curl_init();
        $url = 'https://api.instapago.com/payment';

        /*curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "KeyId=D414AEA3-DBF7-443E-9E0F-DC669D99D544"
            . "&PublicKeyId=e475d9510184b4f73e3b7a2e508e759a"
            . "&Amount=".$result_transaccion['monto_transaccion']
            . "&Description=".$result_transaccion['asunto_transaccion']
            . "&CardHolder=".$result_transaccion['nombre_titular']
            . "&CardHolderID=".$result_transaccion['ci_titular']
            . "&CardNumber=".$result_transaccion['numero_tarjeta']
            . "&CVC=".$result_tmp['codigo']
            . "&ExpirationDate=".$result_transaccion['expiracion']
            . "&StatusId=1"*/

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "KeyId=D414AEA3-DBF7-443E-9E0F-DC669D99D544"
            . "&PublicKeyId=e475d9510184b4f73e3b7a2e508e759a"
            . "&Amount=$monto"
            . "&Description=$asunto"
            . "&CardHolder=$titular"
            . "&CardHolderID=$ci"
            . "&CardNumber=$tarjeta"
            . "&CVC=$cvc"
            . "&ExpirationDate=$mes/$ano"
            . "&StatusId=1"

        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

        $answer = curl_exec($ch);

        if (curl_error($ch)) {
            $error_inesperado = true;
            $success = false;

            break;
        }

        $json_response = json_decode($answer);

        if(is_null($json_response ))
        {
            $success = false;
            break;
        }

        /*
         * 00,10,11 son valores de transaccion exitosa segun instapago
         * El valor por defecto de estatus_transaccion es '1' = Pendiente
         * el valor '3' corresponde a Rechazada
         * **/
        $estatus_transaccion = in_array($json_response->responsecode,["00","10","11"]) ? '2' : '3';
        $emisor_id = $result_transaccion['emisor_id'];


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


        //REVISAR SI LA EMPRESA EN BD ES IGUAL A ESTA EMPRESA

        //Continuar insertando la transaccion
        $transaccion = $conn->prepare("UPDATE transaccion SET id_estatus_transaccion = :estatus_transaccion WHERE id_transaccion = :id_transaccion");

        $transaccion->bindParam(':estatus_transaccion', $estatus_transaccion);
        $transaccion->bindParam(':id_transaccion', $value['id_transaccion']);

        if($transaccion->execute())
        {
            $c_tmp = $conn->prepare("DELETE FROM configuracion_tmp
                                    WHERE tabla_relacionada = :tabla_relacionada AND id_tabla_relacionada = :id_tabla_relacionada");
            $c_tmp->bindParam(':tabla_relacionada', $tabla_relacionada);
            $c_tmp->bindParam(':id_tabla_relacionada', $value['id_transaccion']);
            $c_tmp->execute();

            if($estatus_transaccion == 2)
            {
                /*$c_tmp = $conn->prepare("DELETE FROM configuracion_tmp
                                        WHERE tabla_relacionada = :tabla_relacionada AND id_tabla_relacionada = :id_tabla_relacionada");
                $c_tmp->bindParam(':tabla_relacionada', $tabla_relacionada);
                $c_tmp->bindParam(':id_tabla_relacionada', $value['id_transaccion']);
                $c_tmp->execute();*/

            }
            else
            {
                $success = false;
            }

        }
        else
        {
            $success = false;
        }
    }


}




   echo json_encode(['success'=>$success, 'receptor' => $receptor]);



/*else{
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


}*/



