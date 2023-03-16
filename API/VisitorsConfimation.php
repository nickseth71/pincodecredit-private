<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include '../include/db/Store.php';
include '../include/utils/Shopify.php';
$Shopify = new Shopify();
$Stores = new Stores();
// Since we are inserting data we pass two extra headers.
// 1st allow us to set the method of insert. i.e. POST in rest api
// 2nd determines which type of headers can be sent. It's a secuirty header.
// 'Authorization' is set for authorizing insert data. While 'X-Requested-With' is set for passing data as json

$data = json_decode(file_get_contents("php://input"), true);

$userid = $data['userid'];
$referral = $data['referral'];
$shop = $data['shop'];
$brandId = $Stores->getData("user_id", $shop);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://pincodecredits.com/PincodeAdmin/API/V1/Visitors',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('userid' => $userid, 'referral' => $referral, 'brandid' => json_decode($brandId, true)[0]),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
