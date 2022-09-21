<?php

use PDO;
use PDOException;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    if ($data['Body']['stkCallback']['ResultCode'] == 0) {
        $Item = $data['Body']['stkCallback']['CallbackMetadata']['Item'];

        $metadata = array(
            'MerchantRequestID' => $data['Body']['stkCallback']['MerchantRequestID'],
            'CheckoutRequestID' => $data['Body']['stkCallback']['CheckoutRequestID'],
            'ResultCode' => $data['Body']['stkCallback']['ResultCode'],
            'ResultDesc' => $data['Body']['stkCallback']['ResultDesc'],
        );

        $mpesaData = array_column($Item, 'Value', 'Name');
        $mpesaData = array_merge($metadata, $mpesaData);

        save($mpesaData);

        error_log(print_r($mpesaData, true), 0);
    } else {
        error_log(print_r($data, true), 0);
    }

    echo "{
    'ResponseCode': 0,
    'ResponseDesc': 'Accept Service'
}";
}



function db() {
    global $connect;
    global $table;
    $table = 'stk_payments';
    try {
        $dsn = "mysql:dbhost=localhost;dbname=stk_demo";
        $connect = new PDO($dsn, 'root', 12345);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Throwable $th) {
        error_log($th->getMessage());
        throw $th;
    }
}

function save($data)
{

    db();

    global $connect, $table;

    foreach ($data as $key => $value) {
        $assocKeys[] = $key;
        $bindParams[] = ':' . $key;
        $schemaValues[':' . $key] = $value;
    }

    // insert into users(name, age) values(:name, :age)
    // array(':name' => $name, ':age' => $age)

    $schemaKeys = implode(',', $assocKeys);
    $bindParams = implode(',', $bindParams);
    $formerStatement = "INSERT INTO {$table}({$schemaKeys}) VALUES({$bindParams})";

    try {
        $insertQuery = $connect->prepare($formerStatement);
        $insertQuery->execute((array)$schemaValues);
        $insertedRows = $insertQuery->rowCount();
        error_log("Data Saved", 0);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
    }
}

function getData($limit = 10, $lastID = null) {
    db();

    global $connect, $table;

    $data = $connect->prepare("SELECT * from $table ORDER BY ID DESC LIMIT $limit");
    $data->execute();

    return $data->fetchAll(PDO::FETCH_OBJ);
}