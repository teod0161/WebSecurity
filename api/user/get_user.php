<?php
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";
//endpoint for getting a user's data
if ($_POST['AUTH_TOKEN']) {
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    try {
        $found = false;
        $stmt = $conn->prepare("SELECT avatar_path, username, email  FROM users WHERE user_id = :user_id");

        $stmt->bindParam(':user_id', $user_id);

        // insert a row
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $user_data) {
            $jsonUserData = json_decode("{}");

            $jsonUserData->avatar_path = stripslashes($user_data['avatar_path']);
            $jsonUserData->username = $user_data['username'];
            $jsonUserData->email = $user_data['email'];

            $found = true;
        }
        if ($found) {
            echo json_encode($jsonUserData);
            exit();
        } else {
            header("NOT FOUND", true, 404);
            exit();
        }
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO GET USER", true, 400);
    exit();
}


