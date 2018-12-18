<?php
include "../functions/functions.php";
include "../../jwt.php";
include "../../protected_session.php";
//used to check the rights of a user (admin or standard)
if ($_POST['AUTH_TOKEN']) {
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    if (isAdmin($user_id)) {
        echo true;
        exit();
    }
    echo false;
    exit();

} else {
    header("NOT ENOUGH INFO TO GET USER INFO", true, 400);
    exit();
}


