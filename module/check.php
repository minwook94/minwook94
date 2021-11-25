<?php

session_start();

require "Authenticator.php";


$ga = new Authenticator();

$checkResult = $ga->verifyCode($_SESSION['auth_secret'], $_POST['code'], 0);


if($checkResult) {
    $code = 200;
}else {
    $code = 400;
}

print_r($code);

// if($_SERVER['REQUEST_METHOD'] == 'POST') {
//     header("location: index.php");
//     die();
// }

?>