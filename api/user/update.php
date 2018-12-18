<?php
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["email"] && $_POST['AUTH_TOKEN'] && $_POST['username']) {

    $email = $_POST["email"];
    $username = $_POST["username"];
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;


    try {
        $stmt = $conn->prepare("UPDATE users SET email=:email, username=:username WHERE user_id=:user_id;");

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO UPDATE PROFILE", true, 400);
    exit();
}
