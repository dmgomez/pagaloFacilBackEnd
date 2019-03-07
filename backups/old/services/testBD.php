<?php
/**
 * Created by PhpStorm.
 * User: carlos.duno
 * Date: 05-12-2016
 * Time: 12:46 PM
 */
$servername = "108.167.189.74";
$database = "lotica_pagalofacil";
$username = "lotica_dev";
$password = "a123456";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    echo $conn;
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

echo json_encode($result);