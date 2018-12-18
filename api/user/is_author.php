<?php
include "../functions/functions.php";
include "../../jwt.php";
include "../../protected_session.php";
//is the user of the token the author of a certain article - this checks it
if ($_POST['AUTH_TOKEN'] && $_POST['id']) {
    $article_id = $_POST['id'];
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    try {
        $stmt = $conn->prepare("SELECT user_id FROM articles WHERE id = :article_id AND user_id = :user_id");
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) > 0) {
            echo true;
            exit();
        }
        echo false;
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO GET USER INFO", true, 400);
    exit();
}


