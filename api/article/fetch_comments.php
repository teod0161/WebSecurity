<?php
include "../../cors.php";
include "../../connect_mysql.php";
//id of the requested article
$article_id = $_GET['id'];

try {
    //data for the comments for a requested article
    $comments = [];
    //prepared statement for finding comments for an article
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
        //add the found comment (which belongs to the article) to the array to be sent back    
        array_push($comments, $comment);
    }

    echo json_encode($comments);
    exit();

} catch (Exception $e) {
    header("FAILED OPERATION", false, 500);
    exit();
}