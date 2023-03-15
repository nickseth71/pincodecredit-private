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


$publishThemeID = $Shopify->getPublishThemeID($_SESSION["shop_url"], $_SESSION["access_token"]);
$theme_liquid = 'layout/theme.liquid';
$theme_file = $Shopify->getshopify_assest($_SESSION["shop_url"], $_SESSION["access_token"], $publishThemeID, $theme_liquid);
// echo $theme_file;
$script_code = '<script>if(!document.querySelector("#picodeCreditEmbedJS")) {
    var script = document.createElement("script");
    script.id = "picodeCreditEmbedJS";
    script.src = "' . APP_URL . 'app/assets/js/embed.js ";
    document.head.appendChild(script); }</script>';

if (isset($theme_file['asset'])) {
    $final_template_value = $Shopify->insertString($theme_file['asset']['value'], "</body>", $script_code);
    echo $final_template_value;
    if (isset($final_template_value)) {
        $data_2 = json_encode(array("asset" => array(
            "key" => $theme_file['asset']['key'],
            "value" => $final_template_value
        )), JSON_UNESCAPED_SLASHES);
        $result = $Shopify->updateTemplate($_SESSION["shop_url"], $_SESSION["access_token"], $publishThemeID, $data_2);
        print_r($result);
    }
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
// print_r($oc_webhoo
