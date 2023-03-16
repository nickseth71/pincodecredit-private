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
$script_code = '<script>if(!document.querySelector("#picodeCreditEmbedJS")) {
    var script = document.createElement("script");
    script.id = "picodeCreditEmbedJS";
    script.src = "' . APP_URL . 'app/assets/js/embed.js";
    document.head.appendChild(script); }</script>';

if (isset($theme_file['asset'])) {
    $final_template_value = $Shopify->insertString($theme_file['asset']['value'], "</body>", $script_code);
    if (isset($final_template_value)) {
        $data_2 = json_encode(
            array(
                "asset" => array(
                    "key" => $theme_file['asset']['key'],
                    "value" => $final_template_value
                )
            ),
            JSON_UNESCAPED_SLASHES
        );
        $result = $Shopify->updateTemplate($_SESSION["shop_url"], $_SESSION["access_token"], $publishThemeID, $data_2);
    }
}

$webhook_ORDER_CREATE = array(
    "webhook" => array(
        "topic" => "orders/create",
        "address" => APP_URL . 'webhook/ORDER_CREATE.php?shop=' . $_SESSION["shop_url"],
        "format" => "json"
    )
);

$webhook_THEME_PUBLISH = array(
    "webhook" => array(
        "topic" => "themes/publish",
        "address" => APP_URL . 'webhook/THEME_PUBLISH.php?shop=' . $_SESSION["shop_url"],
        "format" => "json"
    )
);

$oc_webhook = $Shopify->createWebhook($_SESSION["shop_url"], $_SESSION["access_token"], $webhook_ORDER_CREATE);
$tp_webhook = $Shopify->createWebhook($_SESSION["shop_url"], $_SESSION["access_token"], $webhook_THEME_PUBLISH);

echo '<div class="already_exits_mgs text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mgg bi bi-check-circle" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
    </svg>
    Successfully Install for <strong><em>'
    . $_SESSION["shop_url"] .
    '</em></strong>.<br/>If you want Install Other App then <a href="' . APP_URL . 'app/logout.php">click here</a>.</div>';

// echo '<br/><br/><br/>'.$oc_webhook . '<br/> <br/> <br />' . $tp_webhook;