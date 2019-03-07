<?php
/*
if($_POST['action']=="resetPassword")
{
    $email      = mysqli_real_escape_string($connection,$_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Validate email address
    {
        $message =  "Invalid email address please type a valid email!!";
    }
    else
    {
        $query = "SELECT id FROM cliente where email='".$email."'";
        $result = mysqli_query($connection,$query);
        $Results = mysqli_fetch_array($result);
 
        if(count($Results)>=1)
        {
           // $encrypt = md5(1290*3+$Results['id']);
            $encrypt = sha1(1290*3+$Results['id']);
            $message = "Your password reset link send to your e-mail address.";
            $to=$email;
            $subject="Forget Password";
            $from = 'norReply@pagalofacil.com';
            $body='Hi, <br/> <br/>Your Membership ID is '.$Results['id'].' <br><br>Click here to reset your password http://demo.phpgang.com/login-signup-in-php/reset.php?encrypt='.$encrypt.'&action=reset   <br/> <br/>--<br>pagalofacil.com<br>';
            $headers = "From: " . strip_tags($from) . "\r\n";
            $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 
            mail($to,$subject,$body,$headers);
        }
        else
        {
            $message = "Account not found please signup now!!";
        }
    }
}
*/

header("Access-Control-Allow-Origin: *");
include_once '../database/Connection.php';
session_start();
//extract($_POST['payment']);


if(!isset($_POST['email']))
{
    exit(-1);
}


$correo="NADA";
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$usuario = $conn->prepare("SELECT * FROM cliente WHERE correo = ".$_POST['email']);
$usuario->execute();

$result = $usuario->fetch(PDO::FETCH_ASSOC);
//echo $result['cedula'];
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

// $encrypt = md5(1290*3+$result['id']);
            $encrypt = sha1(1290*3+$result['id']); //guardar en BD

$resetEmail = $conn->prepare("INSERT INTO password_reset   (email, emailKey)
                                            VALUES (:correo, :encrypt)"););
$resetEmail->bindParam(':correo', $correo);
$resetEmail->bindParam(':encrypt', $encrypt);
$resetEmail->execute();

            $message = "tu link para reiniciar la contraseña.";
            $to=$email;
            $subject="Reiniciar contraseña pagalofacil";
            $from = 'notReply@pagalofacil.com';
            $body='Hola, <br/> <br/>su ID es '.$result['id'].' <br><br>Click aca para reiniciar tu clave localhost/pagalofacil/services/checkIdEmail.php?encrypt='.$encrypt.'&action=reset   <br/> <br/>--<br>pagalofacil.com<br>';
            $headers = "From: " . strip_tags($from) . "\r\n";
            $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 
            mail($correo,$subject,$body,$headers);
header('Location: http://localhost/pagalofacil');
    exit();
?>