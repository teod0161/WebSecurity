<?php
include "../../cors.php";
include "../../connect_mysql.php";
//endpoint for getting a single article
$article_id = null;
if ($_GET['id']) {
    $article_id = $_GET['id'];
} else {
    header("REQUIRED PARAM ID MISSING", true, 400);
    exit();
}

$response;

try {
    $found = false;
    $stmt = $conn->prepare("SELECT articles.*, users.username, users.avatar_path FROM articles LEFT JOIN users ON users.user_id = articles.user_id  WHERE articles.id=:id  LIMIT 1 ");
    $stmt->bindParam(':id', $article_id);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $article) {
        $jsonArticle = json_decode("{}");
        //setting the data for the article and the user
        $jsonArticle->title = stripslashes($article['title']);
        $jsonArticle->article_data = $article['article_data'];
        $jsonArticle->user_id = $article['user_id'];
        $jsonArticle->id=$article['id'];
        $jsonArticle->username = $article['username'];
        $jsonArticle->avatar_path = $article['avatar_path'];
        $jsonArticle->comments = [];

        $response = $jsonArticle;
        $found = true;
    }

    if (!$found) {
        header("ARTICLE NOT FOUND", false, 404);
        exit();
    }
    //prepared statement for an article
    $stmt = $conn->prepare("SELECT comments.*, users.username, users.avatar_path, users.user_id FROM comments LEFT JOIN users ON users.user_id = comments.user_id WHERE comments.article_id = :article_id ");
    $stmt->bindParam(':article_id', $article_id);
    $stmt->execute();
    
    $result = $stmt->fetchAll();
    foreach ($result as $comm) {
        $comment = json_decode("{}");
        $comment->comment = $comm['comment'];
        $comment->user_id = $comm['user_id'];
        $comment->username = $comm['username'];
        $comment->id = $comm['id'];
        $comment->avatar_path = $comm['avatar_path'];

        array_push($jsonArticle->comments, $comment);
    }

    if ($found) {
        echo json_encode($jsonArticle);
        exit();
    } else {
        header("NOT FOUND", true, 404);
        exit();
    }


} catch (Exception $e) {
    header("FAILED OPERATION", false, 500);
    exit();
}