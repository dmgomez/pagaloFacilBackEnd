<?php
$actual_link ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$encrypt = $_GET['encrypt'];
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$usuario = $conn->prepare("SELECT * FROM passaword_reset where emailKey=:encrypt");
$usuario->bindParam(':encrypt', $encrypt);
$usuario->execute();

$result = $usuario->fetch(PDO::FETCH_ASSOC);
if(!count($result))
{
    $correo = false;
    header('Location: http://localhost/pagalofacil');
    exit();
}
else
{
    $correo = $result['correo'];
    //echo  "CORREO: ".$result['correo'];
    //print_r($result);
}
header('Location: http://localhost/resetClientPassword.php?encrypt='.$encrypt.'&action=reset'); /* Redirect browser */
exit();
?>

