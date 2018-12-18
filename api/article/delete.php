<?php
include "../functions/functions.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST["id"] && $_POST['AUTH_TOKEN']) {
    //deleting an article
    $article_id = $_POST["id"];

    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    try {
        $found = false;
        $stmt = $conn->prepare("SELECT articles.*, users.username, users.avatar_path FROM articles LEFT JOIN users ON users.user_id = articles.user_id  WHERE articles.id=:id  LIMIT 1 ");
        $stmt->bindParam(':id', $article_id);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (count($result) > 0) {
            //signald the required article was found in the list of articles
            $found = true;
        }

        if ($found) {
            foreach ($result as $article) {
               if ($article['user_id'] === $user_id || isAdmin($user_id)) {
                   try {
                       //prepared statement for deleting the found article (if it exists)
                       $stmt = $conn->prepare("DELETE FROM articles WHERE id=:article_id");
                       $stmt->bindParam(':article_id', $article_id);
                       $stmt->execute();

                   } catch (Exception $e) {
                       header("FAILED OPERATION", false, 500);
                       exit();
                   }
               }
            }
        } else {
            header("ARTICLE NOT FOUND", false, 404);
            exit();
        }
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO TO DELETE ARTICLE", true, 400);
    exit();
}


