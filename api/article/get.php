<?php
include "../../cors.php";
include "../../connect_mysql.php";

try {
    $articles = [];
    $stmt = $conn->prepare("SELECT articles.*, users.username, users.avatar_path FROM articles LEFT JOIN users ON users.user_id = articles.user_id");

    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $article) {
        $jsonArticle = json_decode("{}");

        $jsonArticle->title = stripslashes($article['title']);
        $jsonArticle->article_data = $article['description'];
        $jsonArticle->user_id = $article['user_id'];
        $jsonArticle->id=$article['id'];
        $jsonArticle->username = $article['username'];
        $jsonArticle->avatar_path = $article['avatar_path'];

        array_push($articles, $jsonArticle);
    }

    echo json_encode($articles);

} catch (Exception $e) {
    header("FAILED OPERATION", false, 500);
    exit();
}