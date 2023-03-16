<?php
$flag = true;
$e_val = '';
$t_val = '';
if (isset($_SESSION["errors"]) and isset($_SESSION["mgs"])) {
    if ($_SESSION["errors"] === "true") {
        $flag = false;
    }
}
if (isset($_SESSION["access_token"]) and isset($_SESSION["shop_url"])) {
    $e_val = $_SESSION["shop_url"];
    $t_val = $_SESSION["access_token"];
}
if (isset($_SESSION["access_token"]) and isset($_SESSION["shop_url"]) and $flag) {
    echo '<div class="already_exits_mgs text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle mgg warn" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"></path>
    </svg>
    You have already Install for <strong><em>'
        . $_SESSION["shop_url"] .
        '</em></strong>.<br/>If you want Install Other App then <a href="app/logout.php">click here</a>.</div>';
} else {
    echo '<form id="getAccessToken" action="' . $_SERVER['PHP_SELF'] . '" method="POST">
    <div class="mb-3 mt-3">
        <label for="email" class="form-label">Email:</label>
        <input value="" type="email" autocomplete="email" class="form-control" id="email" placeholder="" name="email" required>
        <div class="pc-info">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
        </svg><span>Email us at <a href="mailto:connect@pincodecredits.com" class="">connect@pincodecredits.com</a> to get the login credentials.</span></div>
        </div>

    <div class="mb-3 mt-3">
        <label for="password" class="form-label">Merchant Key:</label>
        <div class="input-group">
        <input value="" type="password" autocomplete="off" class="form-control" id="password" placeholder="" name="password" required>
        <span class="input-group-text toggle-pass">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
        </svg>
        </span>
        </div>
    </div>
    <div class="mb-3 mt-3">
        <label for="shop_url" class="form-label">Store URL:</label>
        <div class="input-group">
            <span class="input-group-text">https://</span>
            <input value="' . $e_val . '" type="text" autocomplete="off" class="form-control" id="shop_url" placeholder="xyz.myshopify.com" name="shop_url" required>
            <div id="invalid_shop" class="invalid-feedback">Invalid store URL</div>
        </div>
    </div>
    <div class="mb-3">
        <label for="access_token" class="form-label">Access Token:</label>
        <input value="' . $t_val . '" type="text" autocomplete="off" class="form-control" id="access_token" placeholder="" name="access_token" required>
    </div>
    <div class="btn-group">
        <button type="submit" class="btn btn-primary">
            <span class="spinner-grow spinner-grow-sm" hidden></span>
            <span class="btn-text">Submit</span>
        </button>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Watch Videos
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" data-bs-toggle="modal" href="#exampleModalToggle" role="button">How to get Shop URL</a>
                </li>
                <li>
                    <a class="dropdown-item" data-bs-toggle="modal" href="#exampleModalToggle2" role="button">How to get Access Token</a>
                </li>
            </ul>
        </div>
    </div>
</form>';
}
