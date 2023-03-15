<?php
$_SESSION["shop_url"] = $shop_url;
$_SESSION["access_token"] = $access_token;

$result = $Shopify->loginByEmail($email);

if ($result[0]['res'] == 'success') {
    if ($password != $result[0]['data'][0]['password']) {
        $_SESSION["errors"] = "true";
        $_SESSION["mgs"] = 'Password is Incorect!';
        header("Location:" . APP_URL);
        exit;
    } else {
        $Stores->updateData(array("user_id" => $result[0]['data'][0]['id']), "store_url = '$shop_url'");
    }
} else {
    $_SESSION["errors"] = "true";
    $_SESSION["mgs"] = 'Email is Incorect!';
    header("Location:" . APP_URL);
    exit;
}



$webhook_ORDER_CREATE =  array(
    "webhook" => array(
        "topic" => "orders/create",
        "address" => APP_URL . 'webhook/ORDER_CREATE.php?shop=' . $_SESSION["shop_url"],
        "format" => "json"
    )
);
$webhook_THEME_PUBLISH = array(
    "webhook" => array(
        "topic" => "orders/create",
        "address" => APP_URL . 'webhook/THEME_PUBLISH.php?shop=' . $_SESSION["shop_url"],
        "format" => "json"
    )
);
$oc_webhook = $Shopify->createWebhook($_SESSION["shop_url"], $_SESSION["access_token"], $webhook_ORDER_CREATE);
$tp_webhook = $Shopify->createWebhook($_SESSION["shop_url"], $_SESSION["access_token"], $webhook_THEME_PUBLISH);
print_r($oc_webhook);
echo '---<br><br>---';
print_r($tp_webhook);
