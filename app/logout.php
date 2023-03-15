<?php
require '../include/db/config.php';
session_start();
session_destroy();
header("Location:" . APP_URL);
exit;
