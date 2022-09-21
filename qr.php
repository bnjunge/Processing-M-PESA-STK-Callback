<?php

$body = array(
    'CPI' => 174379,
    'TrxCode' => 'PB',
    'Amount' => 5,
    'RefNo' => 'BuyG09',
    'MerchantName' => 'Surv Tech'
);

$accessToken = AccessToken();
$response = curlPost($accessToken, $body);
echo "<pre>";
$data = json_decode($response);
echo "</pre>";

echo $data->QRCode;
exit;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
</head>

<body>
    <div style="max-width: 60px;margin:20px auto;">
        <img src="data:image/jpeg;charset=utf-8;base64, <?= $data->QRCode ?>" />
    </div>

</body>

</html>


<?php

function curlPost($token, $data)
{
    $url = "https://sandbox.safaricom.co.ke/mpesa/qrcode/v1/generate";
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS =>  $data,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTPHEADER =>  array('Content-Type: application/json', 'Authorization:Bearer ' . $token),
            CURLOPT_POST =>  1,
            // CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS =>  json_encode($data),
            CURLOPT_RETURNTRANSFER =>  true,
            CURLOPT_SSL_VERIFYPEER =>  false,
            CURLOPT_SSL_VERIFYHOST =>  false
        )
    );
    $response = curl_exec($ch);
    // $request = curl_getinfo($ch);
    curl_close($ch);
    return $response;
}

function AccessToken()
{
    $credentials = array(
        'consumer_key' => 'L1WFwLyO5sYekeaW6v7ZgJPlifqk818j',
        'consumer_secret' => 'teLMgeCkju44TNpW'
    );

    $curl = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_HTTPHEADER => ['Content-Type:application/json; charset=utf8'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_USERPWD => $credentials['consumer_key'] . ':' . $credentials['consumer_secret']
        )
    );
    $result = json_decode(curl_exec($curl));
    curl_close($curl);
    return $result->access_token;
}
