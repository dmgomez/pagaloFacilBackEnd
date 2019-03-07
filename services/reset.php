<?php
/*if(isset($_GET['action']))
{          
    if($_GET['action']=="reset")
    {

        $encrypt = $_GET['encrypt'];
        $email = $_GET['email'];
        $password = $_GET['password'];


        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cliente = $conn->prepare("SELECT * FROM cliente where correo = :email");
        $cliente->bindParam(':correo', $email);
        $cliente->execute();
        $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);

        if(!count($result_cliente))
        {
            
        }
        else
        {
            $usuario = $conn->prepare("SELECT * FROM passaword_reset where email = :email and emailKey=:encrypt");
            $usuario->bindParam(':email', $email);
            $usuario->bindParam(':encrypt', $encrypt);
            $usuario->execute();

            $result = $usuario->fetch(PDO::FETCH_ASSOC);
            if(count($result))
            {
                $res = $conn->prepare("UPDATE cliente SET password = :password WHERE correo = :email");
                $res->bindParam(':password',$password);
                $res->bindParam(':email', $email);
                
                $res->execute();
            }
            else
            {
                
            }
        }

        
    }
}
else*/if(isset($_POST['action']))
{
 
    if($_POST['action']=="reset")
    {

        $encrypt = $_POST['encrypt'];
        $email = $_POST['email'];
        $password = $_POST['password'];


        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cliente = $conn->prepare("SELECT * FROM cliente where correo = :email");
        $cliente->bindParam(':correo', $email);
        $cliente->execute();
        $result_cliente = $cliente->fetch(PDO::FETCH_ASSOC);

        if(!count($result_cliente))
        {
            
        }
        else
        {
            $usuario = $conn->prepare("SELECT * FROM passaword_reset where email = :email and emailKey=:encrypt");
            $usuario->bindParam(':email', $email);
            $usuario->bindParam(':encrypt', $encrypt);
            $usuario->execute();

            $result = $usuario->fetch(PDO::FETCH_ASSOC);
            if(count($result))
            {
                $res = $conn->prepare("UPDATE cliente SET password = :password WHERE correo = :email");
                $res->bindParam(':password',sha1($password));
                $res->bindParam(':email', $email);
                
                $res->execute();
            }
            else
            {
                
            }
        }

        
    }
}
else
{
    header("location: /login-signup-in-php");
}