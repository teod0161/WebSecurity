<?php
include "../../cors.php";
include "../../session.php";
include "../../connect_mysql.php";
include "../../jwt.php";
include "../../protected_session.php";

if ($_POST['AUTH_TOKEN']) {
    $token = $_POST['AUTH_TOKEN'];
    $token_data = explode('.', $token);
    $payloadDecoded = base64UrlDecode($token_data[1]);
    $user_id = json_decode($payloadDecoded)->user_id;

    try {
        $articles = [];
        //prepared stamenet for getting the articles under a certain user
        $stmt = $conn->prepare("SELECT articles.*, users.username, users.avatar_path FROM articles LEFT JOIN users ON users.user_id = articles.user_id WHERE users.user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $article) {
            $jsonArticle = json_decode("{}");
            //setting the data for article    
            $jsonArticle->title = stripslashes($article['title']);
            $jsonArticle->article_data = $article['description'];
            $jsonArticle->user_id = $article['user_id'];
            $jsonArticle->id = $article['id'];
            $jsonArticle->username = $article['username'];
            $jsonArticle->avatar_path = $article['avatar_path'];

            array_push($articles, $jsonArticle);
        }

        echo json_encode($articles);

    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }
} else {
    header("NOT ENOUGH INFO TO GET USER ARTICLES", true, 400);
    exit();
}