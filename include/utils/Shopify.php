<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


class Shopify
{

    private $_APP_KEY;
    private $_APP_SECRET;

    public function __construct()
    {
        // $this->initializeKeys();
    }

    protected function initializeKeys()
    {
        $this->_APP_KEY = SHOPIFY_API_KEY;
        $this->_APP_SECRET = SHOPIFY_SECRET_KEY;
    }

    public function exchangeTempTokenForPermanentToken($shop, $TempCode)
    {
        // encode the data
        $data = json_encode(array("client_id" => $this->_APP_KEY, "client_secret" => $this->_APP_SECRET, "code" => $TempCode));
        // the curl url
        $curl_url = "https://$shop/admin/oauth/access_token";
        // set curl options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // execute curl
        $response = json_decode(curl_exec($ch), true);

        // close curl
        curl_close($ch);
        return $response;
    }

    public function isValidAccessToken($shop, $access_token)
    {
        $curl_url = "https://$shop/admin/shop.json";
        // set curl options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "X-Shopify-Access-Token:$access_token"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // execute curl
        $response = json_decode(curl_exec($ch));

        // close curl
        curl_close($ch);
        if (isset($response->shop->id)) {
            $flagg = true;
        } else {
            $flagg = false;
        }
        return $flagg;
    }

    public function getOrders($shop, $access_token)
    {
        $curl_url = "https://$shop/admin/orders.json?status=any&limit=250";
        // set curl options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "X-Shopify-Access-Token:$access_token"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // execute curl
        $response = json_decode(curl_exec($ch));

        // close curl
        curl_close($ch);
        return $response;
    }

    public function updateProductBody($shop, $access_token, $body)
    {
        $decode_data = array(
            "product" => array(
                "id" => 7409018273982,
                "body_html" => $body
            )
        );

        $data = json_encode($decode_data);
        $curl_url = "https://$shop/admin/api/2023-01/products/7409018273982.json";
        // set curl options

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "X-Shopify-Access-Token:$access_token"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // execute curl
        $response = json_decode(curl_exec($ch));
        // close curl
        curl_close($ch);
        return $response;
    }

    public function createWebhook($shop, $access_token, $decode_data)
    {
        $data = json_encode($decode_data);
        $curl_url = "https://$shop/admin/webhooks.json";
        // set curl options

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "X-Shopify-Access-Token:$access_token"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // execute curl
        $response = json_decode(curl_exec($ch));
        // close curl
        curl_close($ch);
        return $response;
    }

    public function createAssetImage($shop, $access_token, $asset_data)
    {

        $curl_url = "https://$shop/admin/api/2022-01/themes/130133688473/assets.json";
        // set curl options

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "X-Shopify-Access-Token:$access_token"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $asset_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // execute curl
        $response = json_decode(curl_exec($ch));
        // close curl
        curl_close($ch);
        return $response;
    }

    public function getAuthUrl($shop, $scope = null)
    {
        $scopes = ["read_products", "read_orders", "write_orders", "write_products", "read_themes", "write_themes", "read_customers"];
        //print_r($scopes);
        //echo SHOPIFY_API_KEY;
        return 'https://' . $shop . '/admin/oauth/authorize?'
            . 'scope=' . implode("%2C", $scopes)
            . '&client_id=' . SHOPIFY_API_KEY
            . '&redirect_uri=' . SHOPIFY_URL;
    }

    public function validateMyShopifyName($shop)
    {
        $subject = $shop;
        $pattern = '/^(.*)?(\.myshopify\.com)$/';
        preg_match($pattern, $subject, $matches);
        return $matches[2] == '.myshopify.com';
    }

    function validateRequestOriginIsShopify($code, $shop, $timestamp, $signature)
    {
        // $get_params_string = 'code=' . $code . 'shop=' . $shop . 'timestamp=' . $timestamp . '';
        // $calculated_signature = md5(SHOPIFY_APP_PASSWORD . $calculated_signature);
        // if ($calculated_signature == $signature) {
        //     return true;
        // } else if ($_GET['origin'] == 'shopify') {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    public function loginByEmail($email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pincodecredits.com/PincodeAdmin/API/V1/merchant_profile_by_email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email' => $email),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return json_decode($response, true);
    }
}
