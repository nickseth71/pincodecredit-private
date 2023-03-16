<?php
include '../include/db/Store.php';
include '../include/utils/Shopify.php';
$Shopify = new Shopify();
$Stores = new Stores();
try {
    $body_json = file_get_contents('php://input');
    $shop = $_GET["shop"];
    $body = json_decode($body_json, true);

    $publishThemeID = $body['id'];
    $theme_liquid = 'layout/theme.liquid';
    $access_token = $Stores->getData("access_token", $shop);

    $theme_file = $Shopify->getshopify_assest($shop, $access_token, $publishThemeID, $theme_liquid);
    $script_code = '<script>if(!document.querySelector("#picodeCreditEmbedJS")) {
    var script = document.createElement("script");
    script.id = "picodeCreditEmbedJS";
    script.src = "' . APP_URL . 'app/assets/js/embed.js ";
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
                ), JSON_UNESCAPED_SLASHES);
            $result = $Shopify->updateTemplate($shop, $access_token[0], $publishThemeID, $data_2);
            $Shopify->updateProductBody($shop, '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', $result);
        }
    }
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}