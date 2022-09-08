<?php

$data = file_get_contents("php://input");
$data = json_decode($data, true);

$Item = $data['Body']['stkCallback']['CallbackMetadata']['Item'];

$metadata = array(
    'MerchantRequestID' => $data['Body']['stkCallback']['MerchantRequestID'],
    'CheckoutRequestID' => $data['Body']['stkCallback']['CheckoutRequestID'],
    'ResultCode' => $data['Body']['stkCallback']['ResultCode'],
    'ResultDesc' => $data['Body']['stkCallback']['ResultDesc'],
);

$mpesaData = array_column($Item, 'Value', 'Name');
$mpesaData = array_merge($metadata, $mpesaData);

error_log(print_r($mpesaData, true), 0);

echo "{
    'ResponseCode': 0,
    'ResponseDesc': 'Accept Service'
}";