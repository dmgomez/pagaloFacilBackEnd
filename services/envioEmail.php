<?php
header("Access-Control-Allow-Origin: *");
include_once '../database/Connection.php';
session_start();
//extract($_POST['payment']);


if(!isset($_POST['receptor'])){
    exit(-1);
}


$correo="NADA";
//$user_mail=$_POST['user'];
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo "CONSULTA SELECT correo FROM cliente WHERE cedula = ".$user_mail;
$usuario = $conn->prepare("SELECT * FROM cliente WHERE id_cliente = ".$_POST['receptor']);
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