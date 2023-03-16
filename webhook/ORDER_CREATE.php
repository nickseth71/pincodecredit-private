<?php
include '../include/db/Store.php';
include '../include/utils/Shopify.php';
$Shopify = new Shopify();
$Stores = new Stores();

$body_json = file_get_contents('php://input');
$shop = $_GET["shop"];
$body = json_decode($body_json, true);

$total_price = $body['total_price'] ?? null;
$userIdArray = $body['note_attributes'] ?? [];
$userId = null;
foreach ($userIdArray as $value) {
    if ($value['name'] === 'pindoceCreditsUserId') {
        $userId = $value['value'];
        break;
    }
}

if ($userId != null) {
    $tableUserId = $Stores -> getData("user_id",$shop);
    $lineItemDetails = '';
    foreach ($body['line_items'] as $value) {
        $lineItemDetails .= '"Item Title"=> ' . $value['title'] . ', "Item Price"=> ' . '("amount"=>' . $value['price_set']['shop_money']['amount'] . ')' . ', ("currency_code"=>' . $value['price_set']['shop_money']['currency_code'] . ')';
    }
    $saleDescription = '"Order Name"=> ' . $body['name'] . ', "Order ID"=> ' . $body['id'] . ', "Line Items Information"=> { ' . $lineItemDetails . ' }';
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pincodecredits.com/PincodeAdmin/API/V1/GetUserShopping',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'userid' => $userId,
            'amount' => $total_price,
            'saledescription' => $saleDescription,
            'brandid' => $tableUserId,
            'merchantid' => $tableUserId
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}
