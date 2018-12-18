<?php
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["COMMENT_DATA"] && $_POST['AUTH_TOKEN'] && $_POST['ARTICLE_ID']) {

    //comment data and data for the article which received the commend
    $comment_data = $_POST["COMMENT_DATA"];
    $article_id = $_POST["ARTICLE_ID"];
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;


    try {
        //prepared statement to insert the comment in the database for the appropriate article
        $stmt = $conn->prepare("INSERT INTO comments (comment, user_id, article_id) VALUES (:comment_data, :user_id, :article_id)");

        $stmt->bindParam(':comment_data', $comment_data);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':user_id', $user_id);

        // insert a row
        $stmt->execute();
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO ADD ARTICLE", true, 400);
    exit();
}
