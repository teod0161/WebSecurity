<?php
include "../../cors.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["title"] && $_POST["article_data"] && $_POST['AUTH_TOKEN']) {

    //saving the article data
    $article_data = $_POST["article_data"];
    $article_title = addslashes($_POST["title"]);
    $article_description = addslashes($_POST["description"]);
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;


    try {
        //prepared statement for the insertion of the article
        $stmt = $conn->prepare("INSERT INTO articles (title, user_id, article_data, description) VALUES (:title, :user_id, :article_data, :description)");

        $stmt->bindParam(':title', $article_title);
        $stmt->bindParam(':article_data', $article_data);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':description', $article_description);

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

