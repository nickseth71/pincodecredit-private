<?php
session_start();
// print_r($_SESSION);
?>
<!doctype html>
<html>

<head>
  <title>Pincode credits - Installation</title>
  <link rel="icon" type="image/x-icon" href="app/assets/images/favicon.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="app/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="app/assets/css/app.css" rel="stylesheet">
  <script src="app/assets/js/bootstrap.min.js" defer="defer"></script>
  <script src="app/assets/js/app.js" defer="defer"></script>
</head>

<body>
  <div class="container-main">
    <div class="container">
      <header class="header">
        <h1 class="title text-center">Installation - Merchant Form</h1>
        <h2 class="description text-center">Thank you for taking the time to help us improve the platform</h2>
      </header>
      <div class="form-wrap">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          include 'include/db/Store.php';
          include 'include/utils/Shopify.php';
          $Shopify = new Shopify();
          $Stores = new Stores();
          $shop_url = $_REQUEST['shop_url'];
          $access_token = $_REQUEST['access_token'];
          $email = $_REQUEST['email'];
          $password = $_REQUEST['password'];
          if (empty($shop_url) or empty($access_token)) {
            $_SESSION["errors"] = "false";
            include 'app/form.php';
          } else {
            $isValid = $Shopify->isValidAccessToken($shop_url, $access_token);
            if ($isValid) {
              $_SESSION["errors"] = "false";
              $isShopExists = $Stores->isShopExists($shop_url);
              $data = array("store_url" => $shop_url, "access_token" => $access_token);
              if ($isShopExists) {
                $Stores->updateData($data, "store_url = '$shop_url'");
              } else {
                $Stores->addData($data);
              }
              include 'app/actions.php';
            } else {
              $_SESSION["errors"] = "true";
              $_SESSION["mgs"] = 'Access token or Store URL are Invalid.';
              header("Location:" . APP_URL);
            }
          }
        } else {  
          include 'app/form.php';
        }
        ?>
      </div>
    </div>
  </div>

  <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalToggleLabel">How to get Shop URL</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <video width="100%" controls>
            <source src="app/assets/videos/shop_url.mp4" type="video/mp4">
            <source src="app/assets/videos/shop_url.ogg" type="video/ogg">
            Your browser does not support HTML5 video.
          </video>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">
            How to get Access Token
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalToggleLabel2">How to get Access Token</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <video width="100%" controls muted>
            <source src="app/assets/videos/access_token.mp4" type="video/mp4">
            <source src="app/assets/videos/access_token.ogg" type="video/ogg">
            Your browser does not support HTML5 video.
          </video>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">
            How to get Shop URL
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($_SESSION["errors"]) and isset($_SESSION["mgs"])) {
    if ($_SESSION["errors"] === "true") {
      echo '<div class="show position-fixed toast align-items-center text-center border-0 top-0 start-50 translate-middle-x" >
              <div class="d-flex">
                <div class="toast-body">' . $_SESSION["mgs"] . ' </div>
                <button type="button" class="btn-close  me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
            </div>';
    }
  }
  ?>
</body>

</html>