<?php
include "../../cors.php";
include "../../connect_mysql.php";

//    user should have name, password, email, profile data
if ($_POST["username"] && $_POST["password"] && $_POST["email"] && $_POST["avatar"]) {

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        header("INVALID EMAIL ADDRESS", true, 400);
        exit();
    }

    if (strlen($_POST['password']) < 6 || !preg_match('@[A-Z]@', $_POST['password']) || preg_match('@[0-9]@', $_POST['password'])) {
        header("INVALID PASSWORD", true, 400);
        exit();
    }

    if (strlen($_POST['username']) < 4) {
        header("INVALID USERNAME", true, 400);
        exit();
    }

    if (!preg_match('#^data:image/\jpeg;base64,#i', $_POST["avatar"])) {
        header("INVALID USERNAME", true, 400);
        exit();
    }

    $email = $_POST["email"];
    $password = $_POST["password"];
    $options = [
        'cost' => 12
    ];
    $password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
    $username = $_POST["username"];

//    unique user id for the DB
    $user_id = uniqid();

//    avatar data needs to be written in a jpg image
    $avatar_data = $_POST["avatar"];
    $avatar_data = base64_decode(preg_replace('#^data:image/\jpeg;base64,#i', '', $avatar_data));
    $avatar_path = $user_id.".jpg";
    file_put_contents('../public/images/'.$avatar_path, $avatar_data);

//    inserting the user in the database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, avatar_path, user_id)
    VALUES (:username, :email, :password, :avatar_path, :user_id)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':avatar_path', $avatar_path);
        $stmt->bindParam(':user_id', $user_id);

        // insert a row
        $stmt->execute();
        exit();
    } catch (Exception $e) {
        header("FAILED OPERATION", false, 500);
        exit();
    }

} else {
    header("NOT ENOUGH INFO", true, 400);
    exit();
}