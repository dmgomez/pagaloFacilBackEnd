<?php
header("Access-Control-Allow-Origin: *");
if(!isset($_POST['payment'])){
    exit(-1);
}

extract($_POST['payment']);

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

echo $answer;
?>